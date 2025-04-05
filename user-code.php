<?php
require './config/function.php';

if (isset($_POST['updateProfile']))
{
  $firstname = validate($_POST['firstname']);
  $lastname = validate($_POST['lastname']);
  $email = validate($_POST['email']);
  $phone = validate($_POST['phone']);

  $userId = validate($_POST['userId']);
  $user = getById('users',$userId);
  if($user['status'] != 200)
  {
    redirect('my-profile-edit.php?id='. $userId['id'],'No such id found','eror');
  }

  if($firstname != '' && $lastname != '' && $email != '' && $phone != '')
  {
    $query = "UPDATE users SET 
              fname = ?,
              lname = ?,
              email = ?,
              phone = ?
              WHERE id= ? "; 

    $stmt = mysqli_prepare($conn, $query);
    if ($password != '') {
        mysqli_stmt_bind_param($stmt, 'ssssi', $firstname, $lastname, $email, $phone, $userId);
    } else {
        mysqli_stmt_bind_param($stmt, 'ssssi', $firstname, $lastname, $email, $phone, $userId);
    }

    $result = mysqli_stmt_execute($stmt);

    if($result){
      logActivity($userId, "Updated profile");
      redirect('my-profile-edit.php?id='. $userId,'User updated successfully','success');
    }else{
      redirect('my-profile-edit.php?id='. $userId,'Something went wrong','error');
    }
  }
  else 
  {
    redirect('my-profile-edit.php?id='. $userId, 'Please fill all the input fields','warning');
  }
}

?>