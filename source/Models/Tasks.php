<?php

namespace Source\Models;

use Source\Core\Model;

class Tasks extends Model {

    protected $entity = "tasks";

    public static $safe = ['id', 'created_at', 'updated_at'];

    public $response;

    public function __construct()
    {
        $this->response = 
            [ 
                "response_status" => 
                                    [ 
                                        'status' => 1, 
                                        'error_code' => null, 
                                        'msg' => 'Classe ' . __CLASS__ . ' iniciada com sucesso.' 
                                    ]
            ];
    }

    /**
     * @var String $title
     * @var String $status
     * @return Tasks
     */
    public function bootstrap(string $title, string $status = 'pending'): Tasks
    {
        $this->title = trim($title);
        $this->status = trim($status);

        return $this;
    }

    /**
     * @var String $terms
     * @var String $params
     * @var String $columns
     * @return Tasks
     */
    public function find(string $terms, string $params, string $columns = "*") 
    {
        $find = $this->read("SELECT {$columns} FROM " . $this->entity . " WHERE {$terms}", $params);
        if($this->fail() || !$find->rowCount()) {
            return null;
        }
        return $find->fetchAll();
    }

    /**
     * @return Tasks
     */
    public function findAll()
    {
        $stmt = $this->read("SELECT * FROM {$this->entity}");
        if($this->fail() || !$stmt->rowCount()) {
            $this->response['response_status']['msg'] = "Não foi possível obter a lista!";
            $this->response['response_status']['status'] = 0;
            $this->response['response_status']['error'] = $this->fail();
            $this->response['response_data']['all'] = null;
            return $this->response;
        }

        $all = $stmt->fetchAll();

        $this->response['response_data']['all'] = $all;
        $this->response['response_status']['msg'] = "Lista obtida com sucesso!";
        $this->response['response_status']['status'] = 1;

        return $this->response;
    }

    /**
     * @var Array $data
     * @return Tasks
     */
    public function findById($data = null) 
    {
        return $this->find("id = :id", "id={$data['id']}");
    }

    /**
     * @var Array $data
     */
    public function findByStatus($data = null) 
    {
        if ($data['status'] === 'all') {
            return $this->findAll();
        }

        $this->response['response_status']['status'] = 1;
        $this->response['response_data']['obj'] = $this->find("status = :status", "status={$data['status']}");

        return $this->response;
    }

    /**
     * @var Array $data
     * @return Tasks
     */
    public function save($data = null) 
    {

        $this->bootstrap($data['input']);
            
        $taskId = $this->create($this->entity, $this->safe());
        if ($this->fail()) {
            $this->response['response_status']['msg'] = "Não foi possível realizar o cadastro!";
            $this->response['response_status']['status'] = 0;
            $this->response['response_status']['error'] = $this->fail();

            return $this->response;
        }

        $this->data = $this->findById(["id" => $taskId]);

        $this->response['response_status']['status'] = 1;
        $this->response['response_status']['msg'] = "Cadastro realizado com sucesso!";
        $this->response['response_data']['obj'] = $this->data;

        
        return $this->response;

    }

    /**
     * @var Array $data
     * @return Tasks
     */
    public function updateTask($data = null) 
    {

        if (!isset($data['current_status'])) {
            $this->bootstrap($data['title']);
        
            $this->update($this->entity, $this->safe(), "id=:id", "id={$data['task_id']}");
            $this->response['response_status']['msg'] = "Cadastro atualizado com sucesso!";
        }else {
            $status = $data['current_status'] == 0 ? 'finished' : 'pending';
            $this->bootstrap($data['title'], $status);
        
            $this->update($this->entity, $this->safe(), "id=:id", "id={$data['task_id']}");
            $this->response['response_status']['msg'] = "Status atualizado com sucesso!";
        }

        
        if ($this->fail()) {
            $this->response['response_status']['msg'] = "Não foi possível atualizar o cadastro!";
            $this->response['response_status']['status'] = 0;
            $this->response['response_status']['error'] = $this->fail();

            return $this->response;
        }
        $this->data = ($this->findById(["id" => $data['task_id']]));

        $this->response['response_status']['status'] = 1;
        $this->response['response_data']['obj'] = $this->data;

        return $this->response;
        
    }

    /**
     * @var Array $data
     * @return Tasks
     */
    public function deleteTask($data = null) {
        $this->delete($this->entity, "id = :id", $data['task_id']);
        if ($this->fail()) {
            $this->response['response_status']['msg'] = "Não foi possível excluir o cadastro!";
            $this->response['response_status']['status'] = 0;
            $this->response['response_status']['error'] = $this->fail();

            return $this->response;
        }
        $this->data = null;

        $this->response['response_status']['status'] = 1;
        $this->response['response_status']['msg'] = "Cadastro excluído com sucesso!";

        return $this->response;
    }

    /**
     * @var Array $data
     * @return Tasks
     */
    public function deleteAll($data = null) {
        $this->delete($this->entity, "id > :id", 0);
        if ($this->fail()) {
            $this->response['response_status']['msg'] = "Não foi possível excluir os cadastros!";
            $this->response['response_status']['status'] = 0;
            $this->response['response_status']['error'] = $this->fail();

            return $this->response;
        }
        $this->data = null;

        $this->response['response_status']['status'] = 1;
        $this->response['response_status']['msg'] = "Cadastros excluídos com sucesso!";

        return $this->response;
    }

}
