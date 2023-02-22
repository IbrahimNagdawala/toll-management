<script>
    document.onkeypress = function(e) {
        var drop = document.getElementById("drop");
        if (e.keyCode == 97) {

            drop.selectedIndex = 1;


        } else if (e.keyCode==98){
            drop.selectedIndex=2;
        }else{
            drop.selectedIndex=0;
        }
    }
    </script>
    <script>

    function vehicle(p){
        var drop=document.getElementById("drop");
        drop.selectedIndex=p;
    }
</script>
<form method="POST">
    hello : <select name='hello' id='drop' size="3">
        <option value='aniket'>aniket</option>
        <option value='jaya'>jaya</option>
        <option value='kamal'>kamal</option>
    </select>
    <a onclick="vehicle(0)"><img src='images/car.png' width='200px' height='100px'></a>
    <a onclick="vehicle(1)"><img src='images/bus.png' width='200px' height='100px'></a>
    <a onclick="vehicle(2)"><img src='images/truck.jpg' width='200px' height='100px'></a>
    
</form>