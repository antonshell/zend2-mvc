<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Book\Controller;

use Book\Model\Entity\Book;
use Book\Model\Form\BookForm;
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

    public function addAction()
    {
        $form = new BookForm();
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $model = new Book();
            $form->setInputFilter($model->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $model->exchangeArray($form->getData());
                $this->getBooksTable()->saveBook($model);

                // Redirect to list of albums
                return $this->redirect()->toRoute('book');
            }
        }

        return new ViewModel(array(
            'form' => $form
        ));
    }

    public function removeAction() {
        $request = $this->getRequest();
        $response = $this->getResponse();

        /*if ($request->isPost()) {
            $post_data = $request->getPost();
            $book_id = $post_data['id'];



            $this->getBooksTable()->removeBook($book_id);

            if (!$this->getBooksTable()->removeBook($book_id))
                $response->setContent(\Zend\Json\Json::encode(array('response' => false)));
            else {
                $response->setContent(\Zend\Json\Json::encode(array('response' => true)));
            }
        }*/

        $book_id = (int) $this->params()->fromRoute('id', 0);
        if($book_id){
            $this->getBooksTable()->removeBook($book_id);
        }


        return $this->redirect()->toRoute('book');
    }

    public function updateAction(){
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('book', array(
                'action' => 'add'
            ));
        }
        $book = $this->getBooksTable()->getBook($id);

        $form  = new BookForm();
        $form->bind($book);

        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($book->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getBooksTable()->saveBook($form->getData());

                // Redirect to list of albums
                return $this->redirect()->toRoute('book');
            }
        }

        return new ViewModel(array(
            'id' => $id,
            'form' => $form,
        ));
    }
}
