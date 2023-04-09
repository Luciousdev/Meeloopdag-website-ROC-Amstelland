<?php
require '../assets/scripts/sql.php';
checkIfTeacher();
deleteCurrentNews();

?>
<script>
    alert('Nieuws bericht succesvol verwijderd!')
    window.location.replace("control.php");
</script>