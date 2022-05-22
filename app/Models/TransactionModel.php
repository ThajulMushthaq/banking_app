<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Exception;


class TransactionModel extends Model
{
    use HasFactory;
    protected $table = 'transaction';



    public function get_statement($id = 0)
    {
        try {
            $query = DB::table($this->table, 'a')->select('a.*', 'u.email as target', 'u1.email as source')
                ->leftJoin('users as u', 'a.target_id', 'u.id')
                ->leftJoin('users as u1', 'a.source_id', 'u1.id')
                ->where('a.user', $id)
                ->orderBy('a.id', 'DESC');

            return $query->get();
        } catch (Exception $e) {
        }
    }

    public function save_data($data = array(), $id = 0)
    {
        try {
            if ($id > 0) {
                DB::table($this->table)
                    ->where('id', $id)
                    ->update($data);
            } else {
                DB::table($this->table)->insert($data);
            }
        } catch (Exception $e) {
        }
    }

    public function delete_data($id = 0)
    {
        try {
            DB::table($this->table)
                ->where('id', $id)->delete();
        } catch (Exception $e) {
        }
    }
}
