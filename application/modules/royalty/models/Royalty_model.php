<?php defined('BASEPATH') or exit('No direct script access allowed');

class Royalty_model extends MY_Model
{
    public $per_page = 10;

    public function get_authors()
    {
        return $this->db->select('author_name')
            ->from('author')
            ->get()
            ->result();
    }

    public function get_book($author_id)
    {
        return $this->db->select('book_id, book_title, book.draft_id')
            ->from('book')
            ->join('draft_author', 'draft_author.draft_id = book.draft_id')
            ->where('author_id', $author_id)
            ->get()
            ->result();
    }

    public function author_earning($filters)
    {
        $this->db->select('author.author_id, author_name, SUM(qty*price) AS penjualan, SUM(qty*price*book.royalty/100) as earned_royalty')
            ->from('book')
            ->join('draft_author', 'draft_author.draft_id = book.draft_id', 'right')
            ->join('author', 'draft_author.author_id = author.author_id')
            ->join('invoice_book', 'book.book_id = invoice_book.book_id')
            ->join('invoice', 'invoice_book.invoice_id = invoice.invoice_id')
            ->group_by('author.author_id');
        if ($filters['keyword'] != '') {
            $this->db->like('author_name', $filters['keyword']);
        }
        if ($filters['period_end'] != null) {
            //if author.last_paid_date == null
            $this->db->where('issued_date BETWEEN IFNULL(author.last_paid_date, "2000/01/01") and "' . $filters['period_end'] . '"');
        } else {
            $this->db->where('issued_date BETWEEN IFNULL(author.last_paid_date, "2000/01/01") and now()');
        }
        return $this->db->get()->result();
    }

    public function author_details($author_id, $filters)
    {
        $last_paid_date = '';
        if ($filters['last_paid_date'] == '') $last_paid_date = "2000/01/01";
        $this->db->select('book.book_id, book.book_title, SUM(qty) AS count, SUM(qty*price) AS penjualan, SUM(qty*price*book.royalty/100) as earned_royalty')
            ->from('book')
            ->join('draft_author', 'draft_author.draft_id = book.draft_id', 'right')
            ->join('invoice_book', 'book.book_id = invoice_book.book_id')
            ->join('invoice', 'invoice_book.invoice_id = invoice.invoice_id')
            ->where('draft_author.author_id', $author_id);
        if ($filters['period_end'] != null) {
            //if author.last_paid_date == null
            $this->db->where('issued_date BETWEEN "' . $last_paid_date .  '" and "' . $filters['period_end'] . '"');
        } else {
            $this->db->where('issued_date BETWEEN "' . $last_paid_date .  '" and now()');
        }
        return $this->db->get()->result();
    }

    // public function when($params, $data)
    // {
    //     // jika data null, maka skip
    //     if ($data != '') {
    //         if ($params == 'keyword') {
    //             $this->group_start();
    //             $this->or_like('author_name', $data);
    //             $this->group_end();
    //         } else {
    //             $this->group_start();
    //             $this->or_like('invoice.type', $data);
    //             $this->or_like('customer.type', $data);
    //             $this->or_like('status', $data);
    //             $this->group_end();
    //         }
    //     }
    //     return $this;
    // }

    // public function sum_book($book_id)
    // {
    //     $this->db->select('SUM(qty*price) AS total')
    //         ->from('invoice_book')
    //         ->join('invoice', 'invoice.invoice_id = invoice_book.invoice_id', 'left')
    //         ->order_by('total', 'ASC')
    //         // ->where('issued_date', '$filter[start_date')
    //         // ->where('issued_date', '$filters[end_date]')
    //         ->where('book_id', $book_id);
    //     return $this->db->get()->row();
    // }
    // public function validate_invoice()
    // {
    //     $data = array();
    //     $data['input_error'] = array();
    //     $data['status'] = TRUE;

    //     if ($this->input->post('type') == '') {
    //         $data['input_error'][] = 'error-type';
    //         $data['status'] = FALSE;
    //     } else if ($this->input->post('type') == 'cash') {
    //         if ($this->input->post('source') == '') {
    //             $data['input_error'][] = 'error-source';
    //             $data['status'] = FALSE;
    //         }
    //     } else if ($this->input->post('type') == 'credit' || $this->input->post('type') == 'online') {
    //         if ($this->input->post('due-date') == '') {
    //             $data['input_error'][] = 'error-due-date';
    //             $data['status'] = FALSE;
    //         }
    //     }

    //     if ($this->input->post('source') == 'library') {
    //         if ($this->input->post('source-library-id') == '') {
    //             $data['input_error'][] = 'error-source-library';
    //             $data['status'] = FALSE;
    //         }
    //     }

    //     if ($this->input->post('customer-id') == '') {
    //         if ($this->input->post('new-customer-name') == '' && $this->input->post('new-customer-phone-number') == '') {
    //             $data['input_error'][] = 'error-customer-info';
    //             $data['status'] = FALSE;
    //         } else {
    //             if ($this->input->post('new-customer-name') == '') {
    //                 $data['input_error'][] = 'error-new-customer-name';
    //                 $data['status'] = FALSE;
    //             }
    //             if ($this->input->post('new-customer-phone-number') == '') {
    //                 $data['input_error'][] = 'error-new-customer-phone-number';
    //                 $data['status'] = FALSE;
    //             }
    //             if ($this->input->post('new-customer-type') == '') {
    //                 $data['input_error'][] = 'error-new-customer-type';
    //                 $data['status'] = FALSE;
    //             }
    //         }
    //     }

    //     if (empty($this->input->post('invoice_book_id'))) {
    //         $data['input_error'][] = 'error-no-book';
    //         $data['status'] = FALSE;
    //     }

    //     if ($data['status'] === FALSE) {
    //         echo json_encode($data);
    //         exit();
    //     }
    // }

    // public function fetch_invoice_book($invoice_id)
    // {
    //     return $this->db
    //         ->select('invoice_book.*, book.book_title, book.harga')
    //         ->from('invoice_book')
    //         ->join('book', 'book.book_id = invoice_book.book_id')
    //         ->where('invoice_id', $invoice_id)
    //         ->get()
    //         ->result();
    // }

    // public function fetch_book_info($book_id)
    // {
    //     return $this->db
    //         ->select('book_title')
    //         ->from('book')
    //         ->where('book_id', $book_id)
    //         ->get()
    //         ->row();
    // }

    //     // Input buku ke array untuk dropdown
    //     $options = ['' => '-- Pilih --'];
    //     foreach ($books as $book) {
    //         $options += [$book->book_id => $book->book_title];
    //     }

    //     return $options;
    // }

    //     return $options;
    // }

    // public function get_book($book_id)
    // {
    //     $book = $this->select('book.*')
    //         ->where('book_id', $book_id)
    //         ->get('book');

    //     $stock = $this->fetch_warehouse_stock($book_id);

    //     if ($stock == NULL) {
    //         $book->stock = 0;
    //     } else {
    //         $book->stock = $stock->warehouse_present;
    //     }
    //     return $book;
    // }


    // public function filter_invoice($filters, $page)
    // {
    //     $invoice = $this->select(['invoice_id', 'number', 'issued_date', 'due_date', 'invoice.customer_id', 'name as customer_name', 'customer.type as customer_type', 'status', 'invoice.type as invoice_type'])
    //         ->join('customer', 'invoice.customer_id = customer.customer_id', 'left')
    //         ->when('keyword', $filters['keyword'])
    //         ->when('invoice_type', $filters['invoice_type'])
    //         ->when('customer_type', $filters['customer_type'])
    //         ->when('status', $filters['status'])
    //         ->order_by('invoice_id', 'DESC')
    //         ->paginate($page)
    //         ->get_all();

    //     $total = $this->select(['invoice_id', 'number', 'name'])
    //         ->join('customer', 'invoice.customer_id = customer.customer_id', 'left')
    //         ->when('keyword', $filters['keyword'])
    //         ->when('invoice_type', $filters['invoice_type'])
    //         ->when('customer_type', $filters['customer_type'])
    //         ->when('status', $filters['status'])
    //         ->order_by('invoice_id')
    //         ->count();

    //     return [
    //         'invoice'  => $invoice,
    //         'total' => $total
    //     ];
    // }
}
