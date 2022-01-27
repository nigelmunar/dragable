<?php
    
    declare(strict_types=1);

    require_once __DIR__    . '/../application/config/config.php';
    require_once ROOT_PATH  . 'application/includes/init.php';
    require_once ROOT_PATH  . 'application/factories/WidgetDatabaseFactory.php';
    require_once ROOT_PATH  . 'application/factories/PositionDatabaseFactory.php';
    require_once ROOT_PATH  . 'application/factories/CryptoDatabaseFactory.php';
    require_once ROOT_PATH  . 'application/factories/LayoutDatabaseFactory.php';
    require_once ROOT_PATH  . 'application/factories/PageDatabaseFactory.php';

    require ROOT_PATH       . 'application/config/pageVars.php';
    require ROOT_PATH       . 'application/html-includes/front-end/header.php';


    $widgetDB           = \WidgetDatabaseFactory::create();
    $positionDB         = \PositionDatabaseFactory::create();
    $cryptocurrencyDB   = \CryptoDatabaseFactory::create();
    $layoutDB           = \LayoutDatabaseFactory::create();
    $pageDB             = \PageDatabaseFactory::create();


    $widgetTypes        = $widgetDB->getWidgetTypes();
    $crypto             = $cryptocurrencyDB->getCryptocurrencies();
    $page               = $pageDB->getPage($siteURL);
    $layouts            = $layoutDB->getLayouts($page->getPageID());
    $widgets            = $widgetDB->getWidgets($page->getPageID());


    $error              = [];

    
    if (isset($_POST['updateWidget']))
    {
        $content        = substr(trim($_POST['txtEditContent']),0,100);
        $type           = (int)$_POST['selEditWidgetType'];
        $widgetCode     = $_POST['widgetCode'];
        $cryptoCode     = isset($_POST['selEditCryptocurrency']) ? $_POST['selEditCryptocurrency'] : '';
            
        if ($type !== 3 && strlen($content) === 0)
        {
            $error [] = 'content';
            $error [] = 'contentEmtpy';

        }


        if (isset($type) && ($type !== 1 && $type !== 2 && $type !== 3))
        {
            $error [] = 'type';
            $error [] = 'typeInvalid';
        }


        if ($type === 3 && $cryptoCode === '')
        {
            $error [] = 'cryptoCode';
            $error [] = 'cryptoCodeEmpty';
        }


        if (count($error) === 0)
        {

            if ($type !== 3)
            {
                $widgetDB->updateWidget($widgetCode, $content, $type);

                header('location: ' . $siteURL);
                exit();
            }
            else
            {
                $cryptoContent = '';
                $crypto = $cryptocurrencyDB->getCryptoByCode($cryptoCode);

                
                $cryptoContent .= 'Name: ' . $crypto->getName() . ' Symbol: ' . $crypto->getSymbol() . ' Price: ' . $crypto->getPrice();
                $widgetDB->updateWidget($widgetCode, $cryptoContent, $type);

                header('location: ' . $siteURL);
                exit();
            }
        }
    }

    if (isset($_POST['addWidget']))
    {
 
        $content        = substr(trim($_POST['txtWidgetContent']),0,100);
        $type           = (int)$_POST['selWidgetType'];
        $xAxis          = 0;
        $yAxis          = 0;
        $width          = 1;
        $height         = 1;
        $widgetCode     = \Ramsey\Uuid\Uuid::uuid4()->toString();
        $cryptoCode     = isset($_POST['selEditCryptocurrency']) ? $_POST['selEditCryptocurrency'] : '';
        $layoutCode     = $_POST['selLayout'];

        
        if ($type !== 3 && strlen($content) === 0)
        {
            $error [] = 'content';
            $error [] = 'contentEmtpy';

        }


        if ($type !== 1 && $type !== 2 && $type !== 3)
        {
            $error [] = 'type';
            $error [] = 'typeInvalid';
        }


        if ($type === 3 && $cryptoCode === '')
        {
            $error [] = 'cryptoCode';
            $error [] = 'cryptoCodeEmpty';
        }

        $layoutID = $layoutDB->getLayoutID($layoutCode);


        if (count($error) === 0)
        {

            if ($type !== 3)
            {

            
                $widgetID = $widgetDB->addWidget($content, $widgetCode, $type, $page->getPageID());

                $positionDB->addPosition($widgetID, 0,0, 1,1, $layoutID);

                header('location: ' . $siteURL);

                exit();
            }
            else
            {
                $cryptoContent = '';

                $crypto = $cryptocurrencyDB->getCryptoByCode($cryptoCode);

                $cryptoContent .= 'Name: ' . $crypto->getName() . ' Symbol: ' . $crypto->getSymbol() . ' Price: ' . $crypto->getPrice();

                $widgetID = $widgetDB->addWidget($cryptoContent, $widgetCode, $type, $page->getPageID());

                $positionDB->addPosition($widgetID, 0,0, 1,1, $layoutID);

                $cryptocurrencyDB->addCryptoWidget($crypto->getID(), $widgetID);

                header('location: ' . $siteURL);

                exit();

            }
        }
      
    }

    if (isset($_POST['deleteWidget']))
    {
        $code = $_POST['widgetCode'];

        $positionDB->deleteWidget($code);

        header('location: ' . $siteURL);
        
        exit();
    }

    if (isset($_POST['addLayout']))
    {
        $layoutName = substr(trim($_POST['txtLayoutName']), 0,100);

        if (strlen($layoutName) === 0)
        {
            $error [] = 'name';
            $error [] = 'nameEmtpy';
        }

        if($layoutDB->layoutExist($layoutName))
        {
            $error [] = 'name';
            $error [] = 'nameTaken';
        }


        if(count($error) === 0)
        {
            $layoutDB->addLayout($layoutName, $page->getPageID(), \Ramsey\Uuid\Uuid::uuid4()->toString());

            header('location: ' . $siteURL);
            exit();
        }

    }

    if (isset($_POST['deleteLayout']))
    {
        foreach($_POST['chkLayouts'] as $layoutCode)
        {
            $layoutID = $layoutDB->getLayoutID($layoutCode);

            $layoutDB->deleteLayout($layoutID);

            header('location: ' . $siteURL);
            exit();
        }
    }

?>

    <label>Columns
        <input type="number" id="numColumns" name="numColumns" min="1" max="12" value="12" class="form-" style="width: 100px; display: inline; height: 38px; border-radius: 5px; border: none">
    </label>

    <form action="<?php echo $siteURL?>" method="post" class="white-popup mfp-hide" id="addWidget">
        <label>Content:</label>
            <input type="text" class="<?php echo in_array('content', $error) ? 'form-control error' : 'form-control'; ?> " name="txtWidgetContent" id="txtWidgetContent" value style="display: inline;">
            <br>
        <label>Type:</label>
        <select name="selWidgetType" id="selWidgetType"  class="<?php echo in_array('type', $error) ? 'error form-control' : 'form-control'; ?>" >
            <option>Select option</option>
            <?php 
                foreach($widgetTypes as $widgetType)
                {
                    echo '<option value="' . $widgetType['id'] . '">' . $widgetType['type'] . '</option>';
                }
            ?>
        </select>

        <br>

        <div id="cryptoDiv" style="display: none;" >
            <label>Cryptocurrency:</label>
            <select name="selEditCryptocurrency" id="selEditCryptocurrency"  class="<?php echo in_array('type', $error) ? 'error form-control' : 'form-control'; ?>">
                <option>Select option</option>
                <?php foreach($crypto as $coin)
                {
                    echo '<option value="' . $coin->getCryptoCode() . '" >' . $coin->getName() . '</option>';
                }
                ?>
            </select>
            <br>
        </div>

        <br>

        <input type="submit" class="btn" name="addWidget" value="Add Widget" style="font-size: 14px;">
    </form>

    <a href="#addWidget" class="open-popup-link" id="button" onclick="" >Add Widget</a>

    <a href="#addLayout" class="open-popup-link" id="button" onclick="" >Add Layout</a>
    
    <label>Layout:</label>

   
    <select id="selLayout" name="selLayout" class="form-control" style="width: 150px; display:inline" form="addWidget">
    
        <?php 
            foreach($layouts as $layout)
            {
                echo '<option ';

                if ($layout->getActive() === 1)
                {
                    echo 'selected ';
                }

                echo 'value="' . $layout->getLayoutCode() . '">' . $layout->getLayoutName() . '</option>';

            }
        ?>
    
    </select>

    <br clear="all" />
    <br clear="all" />
    <br clear="all" />

    <div class="grid-stack">
    </div>

    <form action="<?php echo $siteURL?>" method="post" class="white-popup mfp-hide" id="editWidgetDiv">
        <label>Content:</label>
            <input type="text" name="txtEditContent" value id="txtEditContent" class="form-control" >
    
        <br clear="all" />

        <label>Type:</label>
        <select name="selEditWidgetType" id="selEditWidgetType" class="form-control">
            <option>Select option</option>
            <?php 
                foreach($widgetTypes as $widgetType)
                {
                    echo '<option value="' . $widgetType['id'] . '">' . $widgetType['type'] . '</option>';
                }
            ?>
        </select>
                
        <br>
        <div id="cryptoEditDiv" style="display: none;" >
            <label>Cryptocurrency:</label>
            <select name="selEditCryptocurrency" id="selEditCryptocurrency"  class="form-control">
                <option>Select option</option>
                <?php foreach($crypto as $coin)
                {
                    echo '<option value="' . $coin->getCryptoCode() . '" >' . $coin->getName() . '</option>';
                }
                ?>
            </select>
        </div>


        <br clear="all" />
        <br clear="all" />

        <input type="hidden" name="widgetCode" value="" id="widgetCode">
        
        <input type="submit" name="deleteWidget" value="DELETE" class="deleteEdit">
        <input type="submit" name="updateWidget" value="UPDATE" class="updateEdit">
    </form>
    <!-- <a><img src="images/settings.png" alt="Settings" style="height: 30px; float: right"></a> -->

    <div id="addLayout" class="white-popup mfp-hide">
        <form action="<?php echo $siteURL?>" method="post">
            <label>Layout Name:</label>
                <input type="text" name="txtLayoutName" value id="txtLayoutName" class="form-control">
            <br>
            <br>
            <input type="submit" name="addLayout" value="Add Layout" class>
        </form>
    </div>

    <br>
    <br>

    <a href="#manageLayouts" id="button" class="open-popup-link" style="background-color: #600022; color: white;" >Manage Layouts</a>

    <table id="manageLayouts" class="table-popup mfp-hide table table-striped" style="width:1000px;">
        <thead>
            <tr>
                <th scope="col">Name</th>
                <th scope="col">Options</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($layouts as $layout) {?>
            <tr>
                <th><?php echo $layout->getLayoutName(); ?></th>
                <th><a href="edit-layout.php?code=<?php echo $layout->getLayoutCode() ?>" style="float: left; margin: 10px;"><img src="images/edit.png" width="12" alt="Edit"></a><a href="edit-layout.php?code=<?php echo $layout->getLayoutCode() ?>" style="float: left; margin: 10px"><img src="images/delete.png" width="12" alt="Delete"></a></th>
            </tr>
            <?php }?>
        </tbody>
    </table>

    <div id="manageLayouts" class="white-popup mfp-hide">
        <form action="<?php echo $siteURL?>" method="post">
           
            <?php foreach($layouts as $layout) {?>

               
                <label for="<?php echo $layout->getLayoutCode() ?>"><?php echo $layout->getLayoutName(); ?></label>
                <input type="checkbox" name="chkLayouts[]" value="<?php echo $layout->getLayoutCode() ?>" style="float: right;" >
                <br>

            <?php }?>

            <br>
            <br>
            <input type="submit" onclick="checkDelete()" name="deleteLayout" value="Remove Selected" class>
        </form>
    </div>
    

</body>
</html>
<script src="<?php echo $siteURL ?>plugins/magnific-popup/jquery.magnific-popup.js"></script>

<script language="JavaScript" type="text/javascript">
    function checkDelete()
    {
        confirm('Are you sure?') ? true : event.preventDefault();
    }
</script>


<?php 
 require ROOT_PATH       . 'application/html-includes/front-end/footer.php';
 require ROOT_PATH       . 'application/html-includes/front-end/pagebottom.php';
?>
