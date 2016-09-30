<!DOCTYPE html>
<html lang="en">
<head>
<title>Telefonkönyv alkalmazás</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js" type="text/javascript" ></script>
<script src="jquery/jquery-3.1.0.min.js" type="text/javascript"></script>
<script src="jquery/jquery.maskedinput.min.js" type="text/javascript"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="http://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js"></script> 
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

<link href="jquery/jquery-ui.css" rel="stylesheet">
<link href="http://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.css" rel="stylesheet">
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">

<style type="text/css">
	table tr th, table tr td{font-size: 1.2rem;}
	.row{ margin:20px 20px 20px 20px;width: 100%;}
	.glyphicon{font-size: 20px;}
	.glyphicon-plus{float: right;}
	a.glyphicon{text-decoration: none;}
	a.glyphicon-trash{margin-left: 10px;}
	.none{display: none;}

	#map {
		height: 550px;
		width: 550px;
		}
</style>

<script>
	function getUsers(){
		$.ajax({
			type: 'POST',
			url: 'userAction.php',
			data: 'action_type=view&'+$("#userForm").serialize(),
			success:function(html){
			$('#userData').html(html);
			}
		});
	}
	
	function userAction(type,id){
		id = (typeof id == "undefined")?'':id;
		var statusArr = {add:"hozzáadás",edit:"módosítás",delete:"törlés"};
		var userData = '';
		
		if (type == 'add') {
			userData = $("#addForm").find('.form').serialize()+'&action_type='+type+'&id='+id;
		}else if (type == 'edit'){
			userData = $("#editForm").find('.form').serialize()+'&action_type='+type;
		}else{
			userData = 'action_type='+type+'&id='+id;
		}
		$.ajax({
			type: 'POST',
			url: 'userAction.php',
			data: userData,
			success:function(msg){
				if(msg == 'ok'){
					alert('A '+statusArr[type]+' sikerült.');
					getUsers();
					$('.form')[0].reset();
					$('.formData').slideUp();
				}else{
					alert('Hiba, próbálja újra.');
				}
			}
		});
	}
	
	function editUser(id){
		$.ajax({
			type: 'POST',
			dataType:'JSON',
			url: 'userAction.php',
			data: 'action_type=data&id='+id,
			success:function(data){
				$('#idEdit').val(data.id);
				$('#nameEdit').val(data.name);
				$('#birthdayEdit').val(data.birthday);
				$('#phoneEdit').val(data.phone);
				$('#zipstateEdit').val(data.zipstate);
				$('#adressEdit').val(data.adress);
				$('#editForm').slideDown();
			}
		});
	}
</script>
</head>

<body>
<div class="container">
    <div class="row">
        <div class="panel panel-default users-content">
            <div class="panel-heading">Felhasználók <a href="javascript:void(0);" class="glyphicon glyphicon-plus" id="addLink" onclick="javascript:$('#addForm').slideToggle();"></a></div>
            <div class="panel-body none formData" id="addForm">
                <h2 id="actionLabel">Hozzáadás</h2>
                
				<form class="form" id="userForm">   
					<div class="form-group">
                        <label>Név</label>
                        <input type="text" class="form-control" name="name"/>
                    </div>
                    <div class="form-group">
                        <label>Születésnap</label>
                        <input type="text" class="form-control" name="birthday" id="datepicker"/>
                            <script><!-- születési idő kiválasztása -->
                                $( function() {
                                $( "#datepicker" ).datepicker({
                                    changeMonth: true,
                                    changeYear: true,
									yearRange: "-100:+0"
                                    });
                                });
                            </script>
                    </div>
                    <div class="form-group">
                        <label>Telefonszám</label>
                        <input type="text" class="form-control" name="phone" id="phone"/>
                            <script type="text/javascript"><!-- telefonszám formátum maszkolása -->
	                            $('#phone').mask('+36-99-999-9999');
                            </script>
                    </div>
					<div class="form-group">
                        <label>Irányítószám, város</label>
                        <input type="text" class="form-control" name="zipstate"/>
                    </div>
                    <div class="form-group">
                        <label>Cím</label>
                       	<input type="text" class="form-control" name="adress" id="pac-input" placeholder="Keresés..."/><!-- címkeresés -->
                    <!-- térkép megjelenítése popupban -->
					<div class="container">
						</br><center><button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">Térkép megjelenítése</button></center>
					
						<div class="modal fade" id="myModal" role="dialog">
							<div class="modal-dialog">
    
						<!-- popup tartalma-->
								<div class="modal-content">
									<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Térkép</h4>
									</div>
									<div class="modal-body">
					
									<!--térkép megjelenítése -->
										<div id="map"></div>
									</div>
								</div>
					
								<div class="modal-footer">
									<button type="button" class="btn btn-default" data-dismiss="modal">Bezárás</button>
								</div>
							</div>
						</div>
					</div>
  
				<script>
				function initAutocomplete() {
				var map = new google.maps.Map(document.getElementById('map'), {
				center: {lat: 45.9765764, lng: 18.0322207},
				zoom: 13,
				mapTypeId: 'roadmap'
				});

      
				var input = document.getElementById('pac-input');
				var searchBox = new google.maps.places.SearchBox(input);
        
				map.addListener('bounds_changed', function() {
				searchBox.setBounds(map.getBounds());
				});

				var markers = [];
        
				searchBox.addListener('places_changed', function() {
				var places = searchBox.getPlaces();

				if (places.length == 0) {
					return;
				}

          
				markers.forEach(function(marker) {
					marker.setMap(null);
				});
				markers = [];
		
				var bounds = new google.maps.LatLngBounds();
				places.forEach(function(place) {
				if (!place.geometry) {
					console.log("Returned place contains no geometry");
					return;
				}
				
				var icon = {
					url: place.icon,
					size: new google.maps.Size(71, 71),
					origin: new google.maps.Point(0, 0),
					anchor: new google.maps.Point(17, 34),
					scaledSize: new google.maps.Size(25, 25)
				};

				markers.push(new google.maps.Marker({
					map: map,
					icon: icon,
					title: place.name,
					position: place.geometry.location
				}));

				if (place.geometry.viewport) {
					bounds.union(place.geometry.viewport);
				} else {
					bounds.extend(place.geometry.location);
				}
				});
				map.fitBounds(bounds);
				});

				jQuery("#myModal").on("shown.bs.modal", function () {
					google.maps.event.trigger(map, "resize");
					map.setCenter(latlng);
				});
				}
				</script>

	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAgquugaxTtEb0W3QUB6dJ2vOWV3kN2tyM&libraries=places&callback=initAutocomplete"
	async defer></script>
			  
                    </div>
                    
					<a href="javascript:void(0);" class="btn btn-warning" onclick="$('#addForm').slideUp();">Mégse</a>
                    <a href="javascript:void(0);" class="btn btn-success" onclick="userAction('add')">Felhasználó hozzáadása</a>
                </form>
            </div>
            
			<div class="panel-body none formData" id="editForm">
                <h2 id="actionLabel">Módosítás</h2>
                <form class="form" id="userForm">
                    <div class="form-group">
                        <label>Név</label>
                        <input type="text" class="form-control" name="name" id="nameEdit"/>
                    </div>
                    <div class="form-group">
                        <label>Születésnap</label>
                        <input type="text" class="form-control" name="birthday" id="birthdayEdit"/>
                            <script> <!-- születési idő módosítása -->
                                $( function() {
                                $( "#birthdayEdit" ).datepicker({
                                    changeMonth: true,
                                    changeYear: true,
									yearRange: "-100:+0"
                                    });
                                });
                            </script>  
                    </div>
                    <div class="form-group">
                        <label>Telefonszám</label>
                        <input type="text" class="form-control" name="phone" id="phoneEdit"/>
                            <script type="text/javascript"><!-- telefonszám maszkolás -->
                            	$('#phoneEdit').mask('+36-99-999-9999');
                            </script>
                    </div>
					<div class="form-group">
                        <label>Irányítószám, város</label>
                        <input type="text" class="form-control" name="zipstate" id="zipstateEdit"/>
                    </div>
                    <div class="form-group">
                        <label>Cím</label>
                        <input type="text" class="form-control" name="adress" id="adressEdit"/>
                    </div>
                    <input type="hidden" class="form-control" name="id" id="idEdit"/>
                    <a href="javascript:void(0);" class="btn btn-warning" onclick="$('#editForm').slideUp();">Mégse</a>
                    <a href="javascript:void(0);" class="btn btn-success" onclick="userAction('edit')">Felhasználó módosítása</a>
                </form>
            </div>
            
			<table class="table table-striped">
                <thead>
                    <tr>
                        <th></th>
                        <th>Név</th>
                        <th>Születésnap</th>
                        <th>Telefonszám</th>
						<th>Irányítószám, város</th>
                        <th>Cím</th>
                        <th>Művelet</th>
                    </tr>
                </thead>
                <tbody id="userData">
                    <?php
                        include 'DB.php';
                        $db = new DB();
                        $users = $db->getRows('users',array('order_by'=>'id DESC'));
                        if(!empty($users)): $count = 0; foreach($users as $user): $count++;
                    ?>
                    <tr>
                        <td><?php echo '#'.$count; ?></td>
                        <td><?php echo $user['name']; ?></td>
                        <td><?php echo $user['birthday']; ?></td>
                        <td><?php echo $user['phone']; ?></td>
						<td><?php echo $user['zipstate']; ?></td>
                        <td><?php echo $user['adress']; ?></td>
                        <td>
                            <a href="javascript:void(0);" class="glyphicon glyphicon-edit" onclick="editUser('<?php echo $user['id']; ?>')"></a>
                            <a href="javascript:void(0);" class="glyphicon glyphicon-trash" onclick="return confirm('Biztosan törölni szeretné?')?userAction('delete','<?php echo $user['id']; ?>'):false;"></a>
                        </td>
                    </tr>
                    <?php endforeach; else: ?>
                    <tr><td colspan="5">Nincs találat......</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>