<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>Inicio</title>
</head>

<body class="flex bg-slate-200 ">
    <nav class="min-w-80 h-screen bg-slate-50 rounded-r-lg py-10 px-4">
        <?php include('NavBar.php') ?>
    </nav>
    <main class="flex-1 p-6">
        <nav class="bg-slate-50 rounded-md mb-5">
        <?php
        $current_page = isset($_GET['page']) ? $_GET['page'] : 'inicio';
        ?>
        <div class="font-bold text-lg uppercase px-4 py-2"><?php echo $current_page; ?></div>
        </nav>
        <?php
        if (isset($_GET['page'])) {
            $page = $_GET['page'];
            switch ($page) {
                case 'inventario':
                    include('./view/Inventario/Index.php');
                    break;
                case 'cliente':
                    include('./view/Cliente/Index.php');
                    break;
                    case 'historialCliente':
                        include('./view/HistorialCliente.php');
                        break;
                default:
                    include('./view/Inicio.php');
            }
        } else {
            include('./view/Inicio.php');
        }
        ?>
    </main>
</body>

</html>

<style>
    body{
        padding: 0;
        margin: 0;
        overflow: hidden;
    }
</style>