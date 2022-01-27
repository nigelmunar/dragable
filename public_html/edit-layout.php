<?php
    
    declare(strict_types=1);

    require_once __DIR__    . '/../application/config/config.php';
    require_once ROOT_PATH  . 'application/includes/init.php';
    require_once ROOT_PATH  . 'application/factories/LayoutDatabaseFactory.php';
    require_once ROOT_PATH  . 'application/factories/PageDatabaseFactory.php';

    require ROOT_PATH       . 'application/config/pageVars.php';
    require ROOT_PATH       . 'application/html-includes/front-end/header.php';


    $layoutDB           = \LayoutDatabaseFactory::create();
    $pageDB             = \PageDatabaseFactory::create();



    if (isset($_GET['code']))
    {
        $layoutCode = $_GET['code'];

        $layout = $layoutDB->getLayoutByCode($layoutCode);
    }

    // echo $scriptName;
    
?>

    <form action="<?php echo $siteURL . 'edit-layout.php'?>" method="post" class="form-inline">
        
        <div class="form-row">
            <div class="col-md-4 mb-3">
                <!-- <label for="txtLayoutName">Layout name</label> -->
                <input type="text" class="form-control" id="txtLayoutName" placeholder="Layout name" value="">
                <div class="valid-feedback">
                    Looks good!
                </div>
            </div>
        </div>

        <input type="submit" name="saveName" value="Save" class="btn">

    
    </form>