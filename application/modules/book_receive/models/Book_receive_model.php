<?php defined('BASEPATH') or exit('No direct script access allowed');

class Book_receive_model extends MY_Model
{

    public $per_page = 10;

    public function get_validation_rules()
    {
        $validation_rules = [
            [
                'field' => 'book_receive_status',
                'label' => $this->lang->line('form_book_receive_status'),
                'rules' => 'trim|required',
            ],
            [
                'field' => 'deadline',
                'label' => $this->lang->line('form_book_receive_deadline'),
                'rules' => 'trim|required',
            ],
            [
                'field' => 'book_receive_id',
                'label' => $this->lang->line('ID Penerimaan Buku'),
                'rules' => 'trim|required',
            ],
        ];

        return $validation_rules;
    }

    public function get_default_values()
    {
        return [
            'book_id'           => '',
            'print_order_id'    => '',
            'order_number'      => '',
            'total'             => '',
            'total_postprint'   => '',
            'book_title'        => '',
            'deadline'          => ''
        ];
    }

    //insert data


    //get & filter data and total of data
    public function filter_book_receive($filters, $page)
    {
        $book_receives = $this->select(['print_order.print_order_id', 
        // 'CONCAT_WS(" - ", NULLIF(book_receive.order_number1,""), print_order.order_number) AS order_number_1', 
        'print_order.order_number',
        'print_order.total', 'print_order.total_postprint', 
        'book.book_id', 
        'book.book_title',
        // 'CONCAT_WS(" - ", NULLIF(book_receive.name,""), book.book_title) AS title',
        'book_receive.*'])
            ->when('keyword', $filters['keyword'])
            ->when('book_receive_status', $filters['book_receive_status'])
            ->join_table('print_order', 'book_receive', 'print_order')
            ->join_table('book', 'book_receive', 'book')
            ->order_by('entry_date', 'DESC')
            ->paginate($page)
            ->get_all();
        $total = $this->select('book_receive_id')
            ->when('keyword', $filters['keyword'])
            ->join_table('print_order', 'book_receive', 'print_order')
            ->join_table('book', 'book_receive', 'book')
            ->count();
        return [
            'book_receives' => $book_receives,
            'total' => $total
        ];
    }

    //get filtered
    public function when($params, $data)
    {
        if ($data) {
            if ($params == 'keyword') {
                $this->group_start();
                // $this->or_like('name', $data);
                $this->like('book_title', $data);
                $this->or_like('order_number', $data);
                $this->group_end();
            }
            if ($params == 'book_receive_status'){
                    $this->where('book_receive_status', $data);
            }
        }
        return $this;
    }

    //get book_id
    public function get_book($book_id)
    {
        return $this->select('book.*')
            ->where('book_id', $book_id)
            ->join_table('book', 'book_receive', 'book')
            ->get('book');
    }
    
    //get print_order_id
    public function get_print_order($print_order_id)
    {
        return $this->select('print_order.*')
            ->where('print_order_id', $print_order_id)
            ->get('print_order');
    }    

    //get book receive id
    public function get_book_receive($book_receive_id)
    {
        return $this->select([
        'print_order.print_order_id',
        'print_order.order_number', 
        'print_order.total', 'print_order.total_postprint', 
        'book.book_id', 
        'book.book_title', 
        'book_receive.*'])
            ->join_table('print_order', 'book_receive', 'print_order')
            ->join_table('book', 'book_receive', 'book')
            ->where('book_receive_id', $book_receive_id)
            ->get();
    }

    public function get_staff_gudang()
    {
        return $this->select(['user_id', 'username', 'level', 'email'])
            ->where('level', 'staff_gudang')
            ->where('is_blocked', 'n')
            ->order_by('username', 'ASC')
            ->get_all('user');
    }

    public function get_staff_gudang_by_progress($progress, $book_receive_id)
    {
        return $this->db->select(['book_receive_user_id', 'book_receive_user.user_id', 'book_receive_id', 'progress', 'username', 'email'])
            ->from('user')
            ->join('book_receive_user', 'user.user_id = book_receive_user.user_id')
            ->where('book_receive_id', $book_receive_id)
            ->where('progress', $progress)
            ->get()->result();
    }

    public function check_row_staff_gudang($book_receive_id, $user_id, $progress)
    {
        return $this->db
            ->where(['book_receive_id' => $book_receive_id, 'user_id' => $user_id, 'progress' => $progress])
            ->get('book_receive_user')
            ->num_rows();
    }

    // public function update_book_receive($book_receive_id, $data, $table){
    //     $this->db->where('book_receive_id', $book_receive_id);
    //     $this->db->update($table, $data);
    // }

    public function delete_book_receive($where){
        $this->db->where('book_receive_id', $where);
        $this->db->delete('book_receive');
    }

    public function start_progress($book_receive_id, $progress)
    {
        // transaction data agar konsisten
        $this->db->trans_begin();

        $input = [
            'book_receive_status' => $progress,
            "{$progress}_start_date" => date('Y-m-d H:i:s')
        ];

        $this->book_receive->where('book_receive_id', $book_receive_id)->update($input);

        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
    }

    public function finish_progress($book_receive_id, $progress)
    {
        $input = [
            'book_receive_status' => "{$progress}_approval",
            "{$progress}_end_date" => date('Y-m-d H:i:s')
        ];

        $update_state = $this->book_receive->where('book_receive_id', $book_receive_id)->update($input);

        if ($update_state) {
            return true;
        } else {
            return false;
        }
    }

}