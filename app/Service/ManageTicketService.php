<?php

namespace App\Service;

use App\Models\TicketCategories;
use App\Models\TicketLabel;

class ManageTicketService {
    public function updateManageTicket($request) {
        $dataReq = $request->all();
        $dataLabel = $dataReq['label'];
        $dataCategories = $dataReq['categories'];
        foreach ($dataLabel as $key => $value) {
            if (isset($value['id'])) {
                TicketLabel::query()->where('id', $value['id'])->update([
                    'name' => $value['name'],
                ]);
            } else {
                TicketLabel::create([
                    'name' => $value['name'],
                ]);
            }
        }
        foreach ($dataCategories as $key => $value) {
            if (isset($value['id'])) {
                TicketCategories::query()->where('id', $value['id'])->update([
                    'name' => $value['name'],
                ]);
            } else {
                TicketCategories::create([
                    'name' => $value['name'],
                ]);
            }
        }
        return response()->json([
            'success' => true,
            'message' => 'Ticket labels and categories updated successfully.',
        ], 200);
    }
}
