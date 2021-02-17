<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TennisRankings;


use Illuminate\Support\Facades\DB;

class TennisRankingsController extends Controller
{
    /**
     * 获取数据
     *
     * @param Request $request
     *   page_size 当前每页数量
     *   page  当前页数
     *
     * @return mixed
     */
    public function get(Request $request)
    {
       /* $input = $request->all();

        $order['field'] = $request->input('prop','id');
        $order['order'] = $request->input('order','asc');

        $data = DB::table('tennis_rankings')->orderBy($order['field'], $order['order'])->paginate($input['page_size']);

        return $this->success($data);*/

        $input = $request->all();

        $order['field'] = $request->input('prop','id');
        $order['order'] = $request->input('order','asc');

        $playerSearch = $request->input('playerSearch') ?? '';

        $Tennis = TennisRankings::orderBy($order['field'], $order['order'])->paginate($input['page_size']);

        if ($playerSearch) {
            $Tennis->where('Player', 'like', "%{$playerSearch}%");
        }

        return $this->success($Tennis,$playerSearch);
    }

    /**
     * 新增条目
     * TODO 验证参数
     * @param Request $request
     *
     */
    public function create(Request $request)
    {
        // TODO验证参数
        $data = $request->validate([
            'date' => 'required|date',
            'gender' => 'required|string',
            'type' => 'required|string',
            'ranking' => 'required|numeric',
            'player' => 'required|string',
            'country' => 'required|string',
            'age' => 'required|numeric',
            'points' => 'required|string',
            'tournaments' => 'required|string',
        ]);

        $this->success($data);

        //unset($data['id']);
        $res = DB::table('tennis_rankings')->insert($data);

        return $this->success($res);
    }

    /**
     * 删除条目
     * @param Request $request
     * @return mixed
     */
    public function delete(Request $request)
    {
        $id = $request->input('id');
        // 验证参数
        if (empty($id)) {
            return $this->fail();
        }
        $res = DB::table('tennis_rankings')->where('id', (int)$id)->delete();
        return $this->success($res);
    }

    /**
     * 更新条目
     * @param Request $request
     * @return mixed
     */
    public function update(Request $request)
    {
        $data = $request->all();
        $id = $data['id'];
        unset($data['id']);

        $data['date'] = date('Y-m-d', strtotime($data['date']));
        $res = DB::table('tennis_rankings')->where('id', $id)->update($data);

        return $this->success($res);
    }

    private function success($data = [], $msg = 'success', $code = 200)
    {
        return response()->json([
            'code' => $code,
            'msg' => $msg,
            'data' => $data,
        ]);
    }

    private function fail($msg = 'fail', $data = [], $code = 400)
    {
        return response()->json([
            'code' => $code,
            'msg' => $msg,
            'data' => $data,
        ]);
    }
}
