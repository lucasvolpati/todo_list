<?php
require_once __DIR__ . "/vendor/autoload.php";


$verify = verifyConnection();

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To Do List | Lucas A. Rodrigues Volpati</title>

    <!-- THEME CSS -->
    <link rel="stylesheet" href="<?= assets('css/style.css') ?>">
</head>
<body>
    <div id="container">
        <?php if($verify['response_status']['status'] == 1): ?>
            <section class="td-panel">
                <div class="td-panel-content">
                    <article class="td-panel-input">
                        <form id="form">
                            <div class="input-group">
                                <span class="input-icon"><i class="fa-solid fa-list-check"></i></span>
                                <input type="text" class="form-control-td" placeholder="Informe o nome da tarefa">
                                <button type="submit" class="group-btn" title="Adicionar tarefa"><i class="fa-solid fa-plus"></i></button>
                            </div>
                        </form>
                    </article>

                    <article class="td-panel-nav">
                        <div class="nav">
                            <nav>
                                <ul>
                                    <li data-id="all" class="menu-item"><a class="active" href="#">Todas<span class="active-border"></span></a></li>
                                    <li data-id="pending" class="menu-item"><a class="" href="#">Pendentes<span></span></a></li>
                                    <li data-id="finished" class="menu-item"><a class="" href="#">Finalizadas<span></span></a></li>
                                </ul>
                            </nav>
                        </div>

                        <div class="nav-button">
                            <button type="button" onclick="deleteAll()" class="btn-td clear-btn"></button>
                        </div>

                    </article>

                    <span class="separator"></span>

                    <article class="td-panel-list">
                        
                    </article>
                </div>
            </section>

            <footer>
                <section class="footer-content">
                    <p>Copyright &copy; <a target="_blank" href="https://github.com/lucasvolpati">Lucas Alcantara</a></p>
                </section>
            </footer>
        <?php else: ?>

            <section class="td-panel ops-panel">
                <h1>Ops!!!</h1>

                <p>Faltou configurar banco de dados!</p>


                <?= "<p>Acesse no seu editor de código: <span style='color: red; font-style: italic;'>'{$verify['response_status']['path']}'</span></p>"; ?>
                
                <button type="button" id="view_example" class="btn-td">Ver Exemplo</button>
                <img id="example_img" style="display: none; margin-top:8px" src="<?= assets('img/code.png') ?>" width="250" alt="">
            </section>

        <?php endif; ?>
    </div>

    <!-- JQUERY -->
    <script src="<?= assets('js/jquery.js') ?>"></script>

    <!-- SWEET ALERT -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- THEME SCRIPTS -->
    <script src="<?= assets('js/scripts.js') ?>"></script>

    <!-- FONTAWESOME -->
    <script src="https://kit.fontawesome.com/860f00444a.js" crossorigin="anonymous"></script>
</body>
</html>