
<nav class=" rounded-r-lg px-4">
    <ul class="flex flex-col gap-5">
        <?php
        $current_page = isset($_GET['page']) ? $_GET['page'] : 'inicio';
        ?>

        <a href="?page=cliente" class="text-base font-semibold py-2 w-[80%] px-4 rounded-md cursor-pointer transition-all duration-200
            <?php echo ($current_page == 'cliente') ? 'bg-slate-600 text-slate-50' : 'bg-slate-200 hover:bg-slate-400 hover:text-slate-200'; ?>
            ">
            Cliente
        </a>

        <a href="?page=inventario" class="text-base font-semibold py-2 w-[80%] px-4 rounded-md cursor-pointer transition-all duration-200
            <?php echo ($current_page == 'inventario') ? 'bg-slate-600 text-slate-50' : 'bg-slate-200 hover:bg-slate-400 hover:text-slate-200'; ?>
            ">
            Inventario
        </a>
    </ul>
</nav>
