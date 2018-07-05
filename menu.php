<?php require_once("Connections/dbConn.php");
$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
?>
<style>
    .responsive-menu-active li h1{
        font-size: 14px;
        text-align: center;
        color: #fff;
        text-transform: uppercase;
        display: block;
        font-weight: 700;
        line-height: 1.5em;
        
    }
</style>
<div class="menu site-bg-color">
            <div class="container">
                <form class="header-search" method="post" id="search_articles">
                    <input type="submit" value="" class="header-search-button">
                    <input type="text" id="search" name="search" placeholder="Search" class="header-search-line">
                </form>
                <div class="responsive-menu">Menu</div>
                <ul class="responsive-menu-active">
                    <li <?php if(strcmp($actual_link, $rootURL) == 0){ ?> class="hactive" <?php } ?>><a href="<?php echo $rootURL; ?>"><h1>Home</h1></a></li>
                    <?php
                        $query_getCat = "SELECT * FROM category WHERE status = '1' ORDER BY category_name";
                        $getCat = mysqli_query($dbConn, $query_getCat) or die(mysqli_error($dbConn));
                    if($getCat){
                        $row_getCat = mysqli_fetch_assoc($getCat);
                        $totalRows_getCat = mysqli_num_rows($getCat);
                        if($totalRows_getCat > 0){ 
                            do{
                                $cnames = $row_getCat['category_name'];
                                $cname = str_replace(' ', '-', $cnames);
                        ?>
                                <li
                                    <?php if (strpos($actual_link, strtolower($cnames)) !== false){ echo 'class="hactive"'; } ?> >
                                    <a href="<?php echo $rootURL; ?>category/<?php echo strtolower($cname);?>"><h1 id="<?php echo $cnames;?>_category_title"><?php echo $cnames;?></h1>
                                    </a>
                    </li>
                        <?php      
                            }while($row_getCat = mysqli_fetch_assoc($getCat));
                        }
                    }
                        
                    ?>
                    <li <?php if (strpos($actual_link, '/about') !== false){ echo 'class="hactive"'; } ?> ><a href="<?php echo $rootURL; ?>about"><h1>About Us</h1></a></li>
                </ul>
            </div>
        </div>
<script>
    var search_form = document.getElementById("search_articles");
    search_form.onsubmit = function(event){
        event.preventDefault();
        var search_val = document.getElementById("search").value;
        var search_length = search_val.length;
        if(search_length > 3){
            search_val = search_val.split(' ').join('-');
            location.href = "<?php echo $rootURL.'search/'; ?>"+search_val;
        }
        else{
            alert("Please enter atleast 4 characters");
            return false;
        }
        
    }
</script>



    <script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-97029256-1', 'auto');
  ga('send', 'pageview');

</script>