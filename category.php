<?php require_once('Connections/dbConn.php');
if(isset($_GET['category'])){
    $category = $_GET['category'];
$query_getCategary = "SELECT * FROM `category` WHERE status = '1' AND category_name = '$category'";
				  $getCategary = mysqli_query($dbConn, $query_getCategary) or die(mysqli_error($dbConn));
                        if($getCategary){
                            $row_getCategary = mysqli_fetch_assoc($getCategary);
                                          ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title><?php echo $row_getCategary['category_title']; ?></title>
    <meta name="description" content="<?php echo $row_getCategary['category_description']; ?>" />
    <meta name="keywords" content="<?php echo $row_getCategary['category_keyword']; ?>"/>
    <meta name="author" content="author name" />
    <link rel="shortcut icon" href="<?php echo $rootURL; ?>images/favicon.ico" type="image/x-icon" />
    <!-- ======================================================================
                                Mobile Specific Meta
    ======================================================================= -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <!-- ======================================================================
                                Style CSS + Google Fonts
    ======================================================================= -->
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,700italic,400,600,700,800" rel="stylesheet" type="text/css">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="<?php echo $rootURL; ?>css/bootstrap.css" />
    <link rel="stylesheet" href="<?php echo $rootURL; ?>css/style.css" />
    <style>
        .menu ul li.hactive a {
            background: #ab0000;
        }
    </style>
</head>

<body>
    <!-- ======================================================================
                                        START HEADER
    ======================================================================= -->
    <div class="header">
        <?php include('header.php');
        include('menu.php');?>
    </div>
    <!-- ======================================================================
                                        END HEADER
    ======================================================================= -->

    <!-- ======================================================================
                                    START CONTENT
    ======================================================================= -->
    <div class="content">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <div class="site-title">
                        <?php echo $row_getCategary['category_name']; ?>
                    </div>
                    <!--Article Content-->
                    <div id="show">

                    </div>

                    <!--Article Content-->
                </div>

                <div class="col-md-4">
                    <div class="sidebar">

                        <?php include("widgets/archives.php"); ?>

                        <?php include("widgets/new-posts.php"); ?>

                        <?php include("widgets/tag-cloud.php"); ?>

                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- ======================================================================
                                    END CONTENT
    ======================================================================= -->


    <!-- =====================================================================
                                         START FOOTER
    ====================================================================== -->
    <?php include("footer.php"); ?>
    <!-- =====================================================================
                                         END FOOTER
    ====================================================================== -->

    <!-- ======================================================================
                                    START SCRIPTS
    ======================================================================= -->
    <script src="<?php echo $rootURL; ?>js/jquery.min.js"></script>
    <script src="<?php echo $rootURL; ?>js/bootstrap.min.js"></script>
    <script src="<?php echo $rootURL; ?>js/jquery-ui.min.js" type="text/javascript"></script>
    <script src="<?php echo $rootURL; ?>js/imagesloaded.pkgd.min.js" type="text/javascript"></script>
    <script src="<?php echo $rootURL; ?>js/jquery.swipebox.min.js" type="text/javascript"></script>
    <script src="<?php echo $rootURL; ?>js/modernizr.custom.63321.js" type="text/javascript"></script>
    <script src="<?php echo $rootURL; ?>js/placeholder.js" type="text/javascript"></script>
    <script src="<?php echo $rootURL; ?>js/masonry.pkgd.min.js" type="text/javascript"></script>
    <script src="<?php echo $rootURL; ?>js/farbtastic/farbtastic.js" type="text/javascript"></script>

    <script src="<?php echo $rootURL; ?>js/options.min.js" type="text/javascript"></script>
    <script src="<?php echo $rootURL; ?>js/plugins.min.js" type="text/javascript"></script>
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->

    <script type="text/javascript">
        var page_number = 0;
        $(document).ready(function() {
            var category = '<?php echo $_GET["category"]; ?>';
            var dataString = {
                "category": category,
                "page_number": page_number
            };
            page_number = page_number + 1;
            $.ajax({
                type: "POST",
                url: "<?php echo $rootURL; ?>content_providers/getArticles.php",
                cache: false,
                data: dataString,
                success: function(html) {
                    $("#show").html(html);
                }
            });
        });

        $(window).scroll(function() {
            if ($(window).scrollTop() == $(document).height() - $(window).height()) {
                $(document).ready(function() {
                    var category = '<?php echo $_GET["category"]; ?>';
                    var dataString = {
                        "category": category,
                        "page_number": page_number
                    };
                    page_number = page_number + 1;
                    $.ajax({
                        type: "POST",
                        url: "<?php echo $rootURL; ?>content_providers/getArticles.php",
                        cache: false,
                        data: dataString,
                        success: function(html) {
                            $("#show").html($("#show").html() + html);
                        }
                    });
                });
            }
        });

    </script>
    <!-- ======================================================================
                                    END SCRIPTS
    ======================================================================= -->
</body>

</html>
<?php }
                                  }?>
