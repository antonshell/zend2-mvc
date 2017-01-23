<?php

namespace Book\Model;

use Book\Model\Entity\Book;
use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Select;

class BookTable extends AbstractTableGateway {

    protected $table = 'books';

    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
    }

    public function fetchAll() {
        $resultSet = $this->select(function (Select $select) {
            $select->order('id ASC');
        });
        $entities = array();
        foreach ($resultSet as $row) {
            $entity = new Book();
            $entity->setId($row->id)
                ->setTitle($row->title)
                ->setIsbn($row->isbn)
                ->setAuthor($row->author);
            $entities[] = $entity;
        }
        return $entities;
    }

    public function getBook($id) {
        $row = $this->select(array('id' => (int) $id))->current();
        if (!$row)
            return false;

        $stickyNote = new Book(array(
            'id' => $row->id,
            'title' => $row->title,
            'isbn' => $row->isbn,
            'author' => $row->author,
        ));
        return $stickyNote;
    }

    public function saveBook(Book $book) {
        $data = array(
            'title' => $book->getTitle(),
            'isbn' => $book->getIsbn(),
            'author' => $book->getAuthor(),
        );

        $id = (int) $book->getId();

        if ($id == 0) {
            if (!$this->insert($data))
                return false;
            return $this->getLastInsertValue();
        }
        elseif ($this->getBook($id)) {
            if (!$this->update($data, array('id' => $id)))
                return false;
            return $id;
        }
        else
            return false;
    }

    public function removeBook($id) {
        return $this->delete(array('id' => (int) $id));
    }
}