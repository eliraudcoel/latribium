<?php
	if(!isset($_SESSION)) 
	{ 
		session_start(); 
	}
?>
</br>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<form method="POST" action="include/getEv.php">
	Evenement:</br>
	<input id="hidden" type="hidden" name="dateEv" value="0"/>
	Date : <input id="date" type="date" name="date" placeholder="Annee-Mois-Jour"/> Heure : <input id="time" type="time" name="time" placeholder="15:00" />
	Titre : <input type="text" name="titreEv"/>
	Description : <textarea name="descEv" rows="3" cols="30"></textarea>
	<input type="submit" class="bt" onclick="myFunction();" />
	<script>
	function myFunction(){
		var date = document.getElementById('date').value;
		var time = document.getElementById('time').value;
		var millisecond = 0;
		date=date.split("-");
		date=date[0]+","+date[1]+","+date[2]+",";
		time=time.split(":");
		var newDate = new Date(date);
		newDate.setHours(time[0]);
		newDate.setMinutes(time[1]);
		newDate.setMilliseconds(millisecond);
		newDate = newDate.getTime();
		document.getElementById('hidden').value = newDate;
	};
	</script>
</form>
<script>
	document.getElementById("Actualités").style.color="#DCDCDC";
</script>