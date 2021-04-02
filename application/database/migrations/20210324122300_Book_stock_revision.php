<?php

class Migration_Book_stock_Revision extends CI_Migration{
    public function up(){
        $this->dbforge->add_field([
            'book_stock_revision_id' => [
                'type' => 'INT',
                'constraint' => 10,
                'auto_increment' => TRUE
            ],
            'book_id' => [
                'type' => 'INT',
                'constraint' => 10,
            ],
            'warehouse_past' => [
                'type' => 'INT',
                'constraint' => 10,
            ],
            'warehouse_present' => [
                'type' => 'INT',
                'constraint' => 10,
            ],
            'warehouse_revision' => [
                'type' => 'INT',
                'constraint' => 10,
            ],
            'operator' => [
                'type' => 'VARCHAR',
                'constraint' => 1,
            ],
            'notes' => [
                'type' => 'VARCHAR',
                'constraint' => '100'
            ]
        ]);
        $this->dbforge->add_key('book_stock_revision_id', TRUE);
        $this->dbforge->create_table('book_stock_revision');
    }

    public function down(){
        $this->dbforge->drop_table('book_stock_revision');
    }
}