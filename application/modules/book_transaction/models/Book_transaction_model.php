<?php defined('BASEPATH') or exit('No direct script access allowed');

class Book_transaction_model extends MY_Model{
    // public function filter_excel($filters)
    public $per_page = 10;
    public function filter_book_transaction($filters, $page)
    {
        $book_transactions = $this->select([
            // 'book.book_id',
            'book.book_title',
            'book_transaction.*'])
            ->when('keyword', $filters['keyword'])
            ->when('start_date', $filters['start_date'])
            ->when('end_date', $filters['end_date'])
            ->join_table('book', 'book_transaction', 'book')
            ->order_by('transaction_date')
            ->paginate($page)
            ->get_all();

        $total = $this->select([
            // 'book.book_id',
            'book.book_title',
            'book_transaction.*'])
            ->when('keyword', $filters['keyword'])
            ->when('start_date', $filters['start_date'])
            ->when('end_date', $filters['end_date'])
            ->join_table('book', 'book_transaction', 'book')
            ->order_by('transaction_date')
            ->paginate($page)
            ->count();
        return [
            'book_transactions' => $book_transactions,
            'total' => $total
        ];
    }

    public function when($params, $data)
    {
        //jika data null, maka skip
        if ($data) {
            if ($params == 'keyword') {
                $this->group_start();
                $this->or_like('book_title', $data);
                $this->group_end();
            }
            else if ($params == 'start_date') {
                $this->where('transaction_date >=', $data);
            }
            else if ($params == 'end_date') {
                $this->where('transaction_date <=', $data);
            }
        }
        return $this;
    }
    public function filter_excel()
    {
        return $this->select(['book.book_title', 'book_stock.book_stock_id', 
        // 'faktur.tanggal_selesai',
        'book_faktur.book_faktur_id', 
        // 'book_receive.book_receive_id', 
        'book_receive.finish_date', 
        'book_transaction.*'])
            // ->when('keyword', $filters['keyword'])
            // ->when('published_year', $filters['published_year'])
            // ->when('warehouse_present', $filters['warehouse_present'])
            ->join_table('book', 'book_transaction', 'book')
            ->join_table('book_stock', 'book_transaction', 'book_stock')
            ->join_table('book_faktur', 'book_transaction', 'book_faktur')
            // ->join_table('faktur', 'book_transaction', 'faktur')
            ->join_table('book_receive', 'book_transaction', 'book_receive')
            ->get_all();
    }
}