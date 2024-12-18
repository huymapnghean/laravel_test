<?php

namespace App\Service;

use App\Models\TableTicketCategory;
use App\Models\TableTicketLabel;
use App\Models\Ticket;
use App\Models\TicketLabel;
use App\Models\User;
use App\Models\UserDetail;
use App\Notifications\SendEmailNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class TicketService {
    public function __construct(Request $request) {
        $this->request = $request;
    }
    public function listTicket($req) {
        $data = Ticket::query()
            ->with(['user_detail', 'ticket_label.label', 'ticket_category.category'])
            ->orderBy('priority', 'asc');
        $payload = $req->attributes->get('jwt_payload');
        if ($payload['role'] === UserDetail::USER) {
            $data->where('user_id', $payload['sub']);
        } elseif ($payload['role'] === UserDetail::AGENT) {
            $data->where('agent_id', $payload['sub']);
        }
        return $data->get();
    }

    public function createTicket($req) {
        $payload = $req->attributes->get('jwt_payload');
        $data = $req->all();

        $dataInsert['title'] = $data['title'];
        $dataInsert['user_id'] = $payload['sub'];
        $dataInsert['message'] = $data['message'];
        $dataInsert['priority'] = intval($data['priority']);
        $dataInsert['created_at'] = date('Y-m-d H:i:s');

        $newID = Ticket::query()->insertGetId($dataInsert);

        foreach ($data['label'] as $label) {
            $temp['label_id'] = $label;
            $temp['ticket_id'] = $newID;
            $temp['created_at'] = date('Y-m-d H:i:s');
            TableTicketLabel::query()->insert($temp);
        }

        foreach ($data['category'] as $category) {
            $tempCategory['category_id'] = $category;
            $tempCategory['ticket_id'] = $newID;
            $tempCategory['created_at'] = date('Y-m-d H:i:s');
            TableTicketCategory::query()->insert($tempCategory);
        }

        if ($newID) {
            $message = 'Co mot ticket vua duoc them moi tu ' . $payload['email'];
            $detail = route('ticket.show', ['id' => $newID]);
            $this->sendEmailNotification($payload['sub'], $message, $detail);
            return response()->json([
                'success' => true,
                'message' => 'Data inserted successfully.',
            ], 201);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Data insertion failed.',
            ], 400);
        }
    }

    public function getTicketByID($id) {
        $payload = $this->request->attributes->get('jwt_payload');
        $data = Ticket::query()
            ->where('id', $id);
        if ($payload['role'] === UserDetail::USER) {
            $data->where('user_id', $payload['sub']);
        } elseif ($payload['role'] === UserDetail::AGENT) {
            $data->where('agent_id', $payload['sub']);
        };
        $data = $data->first();
        if ($data) {
            $data['label'] = TableTicketLabel::query()->where('ticket_id', $id)->pluck('label_id')->toArray();
            $data['category'] = TableTicketCategory::query()->where('ticket_id', $id)->pluck('category_id')->toArray();
            return $data;
        }
        else return null;
    }

    public function updateTicket($id, $req) {
        $payload = $req->attributes->get('jwt_payload');
        $data = $req->all();

        $dataUpdate['title'] = $data['title'];
        $dataUpdate['status'] = $data['status'];
        $dataUpdate['message'] = $data['message'];
        $dataUpdate['priority'] = intval($data['priority']);
        $dataUpdate['updated_at'] = date('Y-m-d H:i:s');

        $affectedRows = Ticket::query()->where('id', $id)->update($dataUpdate);

        if ($affectedRows) {
            $existingLabels = TableTicketLabel::query()
                ->where('ticket_id', $id)
                ->pluck('label_id')
                ->toArray();

            $newLabels = $data['label'];

            $labelsToAdd = array_diff($newLabels, $existingLabels);
            $labelsToRemove = array_diff($existingLabels, $newLabels);

            foreach ($labelsToAdd as $label) {
                TableTicketLabel::query()->insert([
                    'label_id' => $label,
                    'ticket_id' => $id,
                    'created_at' => date('Y-m-d H:i:s'),
                ]);
            }

            if (!empty($labelsToRemove)) {
                TableTicketLabel::query()
                    ->where('ticket_id', $id)
                    ->whereIn('label_id', $labelsToRemove)
                    ->delete();
            }

            $existingCategories = TableTicketCategory::query()
                ->where('ticket_id', $id)
                ->pluck('category_id')
                ->toArray();

            $newCategories = $data['category'];

            $categoriesToAdd = array_diff($newCategories, $existingCategories);
            $categoriesToRemove = array_diff($existingCategories, $newCategories);

            if (!empty($categoriesToAdd)) {
                $insertData = [];
                foreach ($categoriesToAdd as $category) {
                    $insertData[] = [
                        'category_id' => $category,
                        'ticket_id' => $id,
                        'created_at' => date('Y-m-d H:i:s'),
                    ];
                }
                TableTicketCategory::query()->insert($insertData);
            }

            if (!empty($categoriesToRemove)) {
                TableTicketCategory::query()
                    ->where('ticket_id', $id)
                    ->whereIn('category_id', $categoriesToRemove)
                    ->delete();
            }

            return response()->json(['message' => 'Ticket and labels updated successfully']);
        } else {
            return response()->json(['message' => 'No changes were made or Ticket not found'], 404);
        }
    }

    public function deleteTicket($id)
    {
        $ticket = Ticket::query()->find($id);

        if (!$ticket) {
            return response()->json(['message' => 'Ticket not found'], 404);
        }

        TableTicketCategory::query()->where('ticket_id', $id)->delete();
        TableTicketLabel::query()->where('ticket_id', $id)->delete();

        $ticket->delete();
        return response()->json(['message' => 'Ticket deleted successfully'], 200);
    }

    public function updateAgentTicket($req)
    {
        $request = $req->all();
        $agent_id = $request['agent_id'];
        foreach ($request['ticket'] as $ticket) {
            Ticket::query()
                ->where('id', $ticket)
                ->update(['agent_id' => $agent_id]);
        }
        return response()->json(['message' => 'Ticket update successfully'], 200);
    }

    public function sendEmailNotification($userId, $message, $link)
    {
        $user = User::find($userId);

        if ($user) {
            $user->notify(new SendEmailNotification($message, $link));
        }
    }
}
