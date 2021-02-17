<?php

namespace App\Imports;

use App\TennisRankings;
use Maatwebsite\Excel\Concerns\ToModel;

class TennisRankingsImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        if (empty($row[0]) || $row[0] == 'Date') {
            return null;
        }

        return new TennisRankings([
            'date'     => $row[0],
            'gender'     => $row[1],
            'type'     => $row[2],
            'ranking'     => $row[3],
            'player'     => $row[4],
            'country'     => $row[5],
            'age'     => $row[6],
            'points'     => $row[7],
            'tournaments'     => $row[8],
        ]);
    }
}
