/*******************************************************************
 * CLASSES
 ******************************************************************/

class Toast {

    renderToast(code, msg) {
        const Toast = Swal.mixin({
            toast: true,
            position: 'top',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        if (code == 0) {
            return Toast.fire({
                icon: 'error',
                title: msg
            });
        }

        return Toast.fire({
            icon: 'success',
            title: msg
        });
    
        
    }
}

var toast = new Toast

/*******************************************************************
 * MENU CONTROLLER
 ******************************************************************/


var menuItem = document.querySelectorAll(".menu-item")

menuItem.forEach(element => {
    
    element.addEventListener("click", () => {
        element.parentElement.querySelectorAll("li a").forEach(element => element.classList = '')
        element.querySelector("a").classList = 'active'
        element.querySelector("span").classList = 'active-border'

        $.ajax({
            type: "post",
            url: "source/Support/Ajax.php",
            dataType: "json",
            data: {
                'module': 'Tasks',
                'action': 'findByStatus',
                'status': element.dataset.id
            },
            success: function (response) {

                

                if (response.response_data.obj || response.response_data.all) {
                    var obj = response.response_data.obj ? response.response_data.obj : response.response_data.all
                    
                    $(".td-panel-list").html('')

                    /**
                     * Append object data
                     */
                    for (let i = 0; i < obj.length; i++) {
                        $(".td-panel-list").append(`<div class='row-td' id='task${obj[i].id}' data-title='${obj[i].title}' data-status='${obj[i].status == 'pending' ? 0 : 1}'>
                            <div class='col-td'>
                                <button type='button' class='check-btn ${obj[i].status == 'pending' ? '' : ' check-active'}' onclick='checkTask(${obj[i].id})'>
                                    <i class='fa-solid fa-thumbs-up'></i>
                                </button>
                            </div>
                            <div class='col-td'>
                                <p class='${obj[i].status == 'pending' ? '' : ' text-active'}' id='text${obj[i].id}'>${obj[i].title}</p>
                            </div>
                            <div class='col-td'>
                                <button title='Opções' type='button' onclick='openMenu(${obj[i].id})' class='options-btn'>
                                    <i class='fa-solid fa-ellipsis-vertical'></i>
                                    <div data-id='${obj[i].id}' class='nav-options'>
                                        <ul>
                                            <li onclick='updateTask(${obj[i].id})'><i class="fa-solid fa-pen"></i>Editar</li>
                                            <li onclick='deleteTask(${obj[i].id})'><i class="fa-solid fa-trash"></i>Excluir</li>
                                        </ul>
                                    </div>
                                </button>
                            </div>
                        </div>`)
    
                    }
                }else {
                    $(".td-panel-list").html("<p class='empty-msg'>Não há registros para exibir!</p>")
                }
            
                
            }
        });
    })
});


/*******************************************************************
 * GET DATA TO INITIAL VIEW
 ******************************************************************/

$(document).ready(() => {
    $.ajax({
        type: "post",
        url: "source/Support/Ajax.php",
        dataType: "json",
        data: {
            'module':'Tasks',
            'action': 'findAll'
        },
        success: function (response) {

            if (response.response_status.status == 1) {
                var list = response.response_data.all

                /**
                 * Append object data
                 */
                list.forEach(obj => {

                    $(".td-panel-list").append(`<div class='row-td' id='task${obj.id}' data-title='${obj.title}' data-status='${obj.status == 'pending' ? 0 : 1}'>
                        <div class='col-td'>
                            <button type='button' class='check-btn ${obj.status == 'pending' ? '' : ' check-active'}' onclick='checkTask(${obj.id})'>
                                <i class='fa-solid fa-thumbs-up'></i>
                            </button>
                        </div>
                        <div class='col-td'>
                            <p class='${obj.status == 'pending' ? '' : ' text-active'}' id='text${obj.id}'>${obj.title}</p>
                        </div>
                        <div class='col-td'>
                            <button title='Opções' type='button' onclick='openMenu(${obj.id})' class='options-btn'>
                                <i class='fa-solid fa-ellipsis-vertical'></i>
                                <div data-id='${obj.id}' class='nav-options'>
                                    <ul>
                                        <li onclick='updateTask(${obj.id})'><i class="fa-solid fa-pen"></i>Editar</li>
                                        <li onclick='deleteTask(${obj.id})'><i class="fa-solid fa-trash"></i>Excluir</li>
                                    </ul>
                                </div>
                            </button>
                        </div>
                    </div>`)
                });
            }else {
                $(".td-panel-list").html("<p class='empty-msg'>Não há registros para exibir!</p>")
            }

            
        }
    });
})


/*******************************************************************
 * FORM
 ******************************************************************/

var form = document.querySelector('#form');

if (form) {
    form.addEventListener("submit", (event) => {
        event.preventDefault()

        if (form.querySelector("input").value != '') {
        $.ajax({
                type: "post",
                url: "source/Support/Ajax.php",
                dataType: "json",
                data: {
                    'module':'Tasks',
                    'action': 'save',
                    'input': form.querySelector("input").value
                },
                success: function (response) {

                    form.querySelector("input").value = ''

                    toast.renderToast(1, 'Tarefa cadastrada com sucesso!')

                    /**
                     * Append object data
                     */
                    var obj = response.response_data.obj
                    $(".td-panel-list").append(`
                    <div class='row-td' id='task${obj[0].id}' data-title='${obj[0].title}' data-status='${obj[0].status == 'pending' ? 0 : 1}'>
                        <div class='col-td'>
                            <button type='button' class='check-btn' onclick='checkTask(${obj[0].id})'>
                                <i class='fa-solid fa-thumbs-up'></i>
                            </button>
                        </div>
                        <div class='col-td'>
                            <p id='text${obj[0].id}'>${obj[0].title}</p>
                        </div>
                        <div class='col-td'>
                            <button title='Opções' type='button' onclick='openMenu(${obj[0].id})' class='options-btn'>
                                <i class='fa-solid fa-ellipsis-vertical'></i>
                                <div data-id='${obj[0].id}' class='nav-options'>
                                    <ul>
                                        <li onclick='updateTask(${obj[0].id})'><i class="fa-solid fa-pen"></i>Editar</li>
                                        <li onclick='deleteTask(${obj[0].id})'><i class="fa-solid fa-trash"></i>Excluir</li>
                                    </ul>
                                </div>
                            </button>
                        </div>
                    </div>`)

                    

                    var empty = document.querySelector('.empty-msg')

                    if (empty) {
                        empty.remove();
                    }
                    

                },
                error: function(jqXHR, textStatus, errorThrown) {
                    
                    toast.renderToast(0, 'Erro ao cadastrar. Status: ' + textStatus)
                }
            }); 
        }else{
            toast.renderToast(0, 'O nome da tarefa não pode ser vazio!')
        }
        
    })
}


/*******************************************************************
 * UPDATING
 ******************************************************************/

/**
 * Update status
 * @param {int} id 
 */
function checkTask(id) {

    $.ajax({
        type: "post",
        url: "source/Support/Ajax.php",
        dataType: "json",
        data: {
            'module':'Tasks',
            'action': 'updateTask',
            'task_id': id,
            'title': document.querySelector(`#task${id}`).dataset.title,
            'current_status': document.querySelector(`#task${id}`).dataset.status
        },
        success: function (response) {

            toast.renderToast(1, 'Tarefa atualizada com sucesso!')

            //Update view
            var item = document.querySelector(`#task${id}`);

            if (response.response_data.obj[0].status == 'finished') {
                
                item.querySelector(".check-btn").classList = 'check-btn check-active'
                item.querySelector(`#text${id}`).classList = 'text-active'
                item.dataset.status = response.response_data.obj[0].status == 'pending' ? 0 : 1
            }else {
                item.querySelector(".check-btn").classList = 'check-btn'
                item.querySelector(`#text${id}`).classList = ''
                item.dataset.status = response.response_data.obj[0].status == 'pending' ? 0 : 1
            }

            //Verifying activated menu
            var menu = document.querySelectorAll('.menu-item')

            menu.forEach(element => {
                var tag = element.querySelector("a")

                if (tag.classList.contains("active") && element.dataset.id != 'all') {
                    document.querySelector(`#task${id}`).remove()

                    if (document.querySelectorAll('.row-td').length == 0) {
                        $(".td-panel-list").html("<p class='empty-msg'>Não há registros para exibir!</p>")
                    }
                }
            });
            
        },
        error: function(jqXHR, textStatus, errorThrown) {
            
            toast.renderToast(0, 'Erro ao atualizar status. Status: ' + textStatus)
        }
    });
}

/**
 * Update title/task name
 * @param {int} id 
 */
async function updateTask(id) {

    const { value: title } = await Swal.fire({
        title: 'Atualizar tarefa',
        input: 'text',
        inputValue: document.querySelector(`#task${id}`).dataset.title
    })
    
    if (title) {
        $.ajax({
            type: "post",
            url: "source/Support/Ajax.php",
            dataType: "json",
            data: {
                "module": 'Tasks',
                "action": 'updateTask',
                "task_id": id,
                'title': title
            },
            success: function (response) {

                toast.renderToast(1, 'Tarefa atualizada com sucesso!')

                $(`#text${id}`).html(title)
            },
            error: function(jqXHR, textStatus, errorThrown) {
                
                toast.renderToast(0, 'Erro ao atualizar tarefa. Status: ' + textStatus)
            }
        });
    }
}

/*******************************************************************
 * DELETE
 ******************************************************************/

/**
 * Delete by id
 * @param {int} id 
 */
function deleteTask(id) {

    $.ajax({
        type: "post",
        url: "source/Support/Ajax.php",
        dataType: "json",
        data: {
            "module": 'Tasks',
            "action": 'deleteTask',
            "task_id": id
        },
        success: function (response) {

            document.querySelector(`#task${id}`).remove()

            if (document.querySelectorAll('.row-td').length == 0) {
                $(".td-panel-list").html("<p class='empty-msg'>Não há registros para exibir!</p>")
            }

            toast.renderToast(1, 'Tarefa excluída com sucesso!')
        },
        error: function(jqXHR, textStatus, errorThrown) {
            
            toast.renderToast(0, 'Erro ao excluir tarefa. Status: ' + textStatus)
        }
    });
}

/**
 * Delete all registers
 */
function deleteAll() {
   
    Swal.fire({
        title: 'Tem certeza?',
        text: "Você irá excluir todas as tarefas!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim, excluir!'
      }).then((result) => {
        if (result.isConfirmed) {
        
            
            $.ajax({
                type: "post",
                url: "source/Support/Ajax.php",
                dataType: "json",
                data: {
                    "module": 'Tasks',
                    "action": 'deleteAll'
                },
                success: function (response) {
                    
                    toast.renderToast(1, 'Todas as tarefas foram excluidas com sucesso!')

                    $(".td-panel-list").html("<p class='empty-msg'>Não há registros para exibir!</p>")
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    
                    toast.renderToast(0, 'Erro ao excluir todas as tarefas. Status: ' + textStatus)
                }
            });
        }
      })
}

/*******************************************************************
 * OTHERS FUNCTIONS
 ******************************************************************/

/**
 * 
 * @param {int} id 
 */
function openMenu(id) {

    const menu = $(`[data-id=${id}]`)
    $(menu).css("display", 'flex')

    $(menu).on("mouseleave", () => {
        $(menu).css("display", 'none')
    })
}

/*******************************************************************
 * OPS PANEL
 ******************************************************************/
var show = 1
$("#view_example").on("click", () => {
    
    if (show == 1) {
        $("#example_img").css("display", 'flex')
        $("#view_example").text("Esconder")
        show = 0
    }else {
        $("#example_img").css("display", 'none')
        $("#view_example").text("Ver Exemplo")
        show = 1
    }
    
})
