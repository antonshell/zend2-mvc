<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Book\Controller;

use Book\Model\Entity\Book;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class BooksController extends AbstractActionController
{
    protected $_booksTable;
    
    public function getBooksTable() {
        if (!$this->_booksTable) {
            $sm = $this->getEvent()->getApplication()->getServiceManager();
            $this->_booksTable = $sm->get('Book\Model\BookTable');
        }
        return $this->_booksTable;
    }

    public function indexAction() {
        return new ViewModel(array(
            'books' => $this->getBooksTable()->fetchAll(),
        ));
    }
    public function addAction() {
        $request = $this->getRequest();
        $response = $this->getResponse();
        if ($request->isPost()) {
            //$new_note = new \StickyNotes\Model\Entity\StickyNote();
            $new_book = new Book();
            if (!$book_id = $this->getBooksTable()->saveBook($new_book))
                $response->setContent(\Zend\Json\Json::encode(array('response' => false)));
            else {
                $response->setContent(\Zend\Json\Json::encode(array('response' => true, 'new_note_id' => $book_id)));
            }
        }
        return $response;
    }

    public function removeAction() {
        $request = $this->getRequest();
        $response = $this->getResponse();
        if ($request->isPost()) {
            $post_data = $request->getPost();
            $book_id = $post_data['id'];
            if (!$this->getBooksTable()->removeBook($book_id))
                $response->setContent(\Zend\Json\Json::encode(array('response' => false)));
            else {
                $response->setContent(\Zend\Json\Json::encode(array('response' => true)));
            }
        }
        return $response;
    }

    public function updateAction(){
        // update post
        $request = $this->getRequest();
        $response = $this->getResponse();
        if ($request->isPost()) {
            $post_data = $request->getPost();
            $book_id = $post_data['id'];
            $note_content = $post_data['content'];
            $book = $this->getBooksTable()->getBook($book_id);
            $book->setTitle($note_content);
            if (!$this->getBooksTable()->saveBook($book))
                $response->setContent(\Zend\Json\Json::encode(array('response' => false)));
            else {
                $response->setContent(\Zend\Json\Json::encode(array('response' => true)));
            }
        }
        return $response;
    }
}
