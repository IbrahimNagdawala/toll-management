<form>
rs1 : <input type="text" name="rs1" value="0" onchange="hello()" oninput="hello()">
rs2 : <input type="text" name="rs2" value="0" onchange="hello()">
cash total : <input type="text" readonly name="cash" id="cash_total" value="0">
<input type="button" name="submit" value="submit" >
</form>
<script>
function hello(){
  var rs1= document.getElementsByName("rs1")[0].value;
  var rs2=document.getElementsByName("rs2")[0].value *2;
  
  var displaycash=document.getElementById("cash_total");
  var cash=parseInt(rs1)+parseInt(rs2);
  displaycash.value=cash;
  


}
</script>

