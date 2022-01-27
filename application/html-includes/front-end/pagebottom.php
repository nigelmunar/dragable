<script>
function editWidget(code)
{
    $('#widgetCode').val(code);
}

$(function()
{   

    let options = { column: 12, celHeight: 'initial', animate: false, disableOneColumnMode: true, float: true};

    var grid = GridStack.init(options);

    let layout = 'moveScale';

    (function getWidgets()
    {
        $.ajax(
        {
            url : '<?php echo $siteURL ?>ajax/widgets.php',
            success: function(data)
            {
                elements = JSON.parse(data);

                grid.removeAll();
               
                $.each(elements, function(key, data)
                {
                    var widget =
                    {
                        x: data.x,
                        y: data.y,
                        content: '<div class="widgetHeader"><a href="#editWidgetDiv" data-code="' + data.code + '" class="open-popup-link" onclick="editWidget($(this).data(\'code\'));"><img scr="images/settings.png" alt="Edit"></a></div><br>' + data.content,
                        id: data.id,
                        w: data.w,
                        h: data.h
                    };

                    grid.addWidget(widget);

                });

                $('.open-popup-link').magnificPopup(
                {
                    type: 'inline',
                    preloader: false,
                    focus: 'input',
                    midClick: true,
                })
            },
            // complete: function() 
            // {
            //     setTimeout(getWidgets, 5000);
            // }
        })
    })();


    $('.open-popup-link').magnificPopup(
    {
        type: 'inline',
        preloader: false,
        focus: 'input',
        midClick: true,
    })

    grid.on('dragstop', function(event, el) 
    {
        let node = el.gridstackNode;

        let x = el.getAttribute('gs-x'); 
        let y = el.getAttribute('gs-y');
        let id = parseInt(el.getAttribute('gs-id'));

        $.ajax({
            type: 'POST',
            url: '/ajax/draggable.php',
            data: {x: x, y: y, id:id, drag:'drag'}
        })


    });


    grid.on('resizestop', function(event, el) 
    {
        let node = el.gridstackNode;

        let w = parseInt(el.getAttribute('gs-w'));
        let h = parseInt(el.getAttribute('gs-h'));
        let id = parseInt(el.getAttribute('gs-id'));

        $.ajax({
            type: 'POST',
            url: '/ajax/draggable.php',
            data: {w: w, h: h, id:id, resize:'resize'}
        });
    
    });

    $('#numColumns').on('input', function() 
    {
        var number = $(this).val();

        if(Math.floor(number) == number && $.isNumeric(number))
        {
            grid.column(number);
        }
    });

    $('.open-popup-link').magnificPopup(
    {
        type: 'inline',
        preloader: false,
        focus: 'input',
        midClick: true,

    });

    function resizeGrid() 
    {
        let width = document.body.clientWidth;

        if (width < 700) 
        {
            grid.column(1, layout).cellHeight('100vw');
            $('#numColumns').val(1);

        } else if (width < 850) 
        {
            grid.column(3, layout).cellHeight('33.3333vw');
            $('#numColumns').val(3);

        } else if (width < 950) 
        {
            grid.column(6, layout).cellHeight('16.6666vw');
            $('#numColumns').val(6);

        } else if (width < 1100) 
        {
            grid.column(8, layout).cellHeight('12.25vw');
            $('#numColumns').val(8);
        } 
        else 
        {
            grid.column(12, layout).cellHeight('8.3333vw');
            $('#numColumns').val(12);
        }
    };

    resizeGrid(); 

    window.addEventListener('resize', function() {resizeGrid()});

    $('#selWidgetType').change(function()
    {
        if($('#selWidgetType option:selected').val() == 3)
        {
            $('#cryptoDiv').show();
        }
        else
        {
            $('#cryptoDiv').hide();
        }
    })

    $('#selEditWidgetType').change(function()
    {
        if($('#selEditWidgetType option:selected').val() == 3)
        {
            $('#cryptoEditDiv').show();
        }
        else
        {
            $('#cryptoEditDiv').hide();
        }
    })

    $('#selLayout').change(function()
    {
        $.ajax(
        {
            url : '<?php echo $siteURL ?>ajax/layout.php?code=' + $(this).val() + '&page=' + '<?php echo $page->getPageID(); ?>',
            success: function(data)
            {
                elements = JSON.parse(data);

                grid.removeAll();
               
                $.each(elements, function(key, data)
                {
                    var widget =
                    {
                        x: data.x,
                        y: data.y,
                        content: '<div class="widgetHeader"><a href="#editWidgetDiv" data-code="' + data.code + '" class="open-popup-link" onclick="editWidget($(this).data(\'code\'));"><img scr="images/settings.png" alt="Edit"></a></div><br>' + data.content,
                        id: data.id,
                        w: data.w,
                        h: data.h
                    };

                    grid.addWidget(widget);

                });

                $('.open-popup-link').magnificPopup(
                {
                    type: 'inline',
                    preloader: false,
                    focus: 'input',
                    midClick: true,
                })
            },
        });
    })
    
});




</script>
