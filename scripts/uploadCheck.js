  function upload_check() {
    const mainPic = document.querySelector('input[name="mainbilder[]"]');
    const outsidePics = document.querySelector('input[name="outsidebilder[]"]');
    const insidePics = document.querySelector('input[name="insidebilder[]"]');
    

    if ((!mainPic.files || mainPic.files.length === 0) && 
        (!outsidePics.files || outsidePics.files.length === 0) && 
        (!insidePics.files || insidePics.files.length === 0)) {
        alert("Bitte mindestens ein Bild auswählen!");
        return false;
    }
    
    
    return true;
}


  // eventlistener for submitbutton
  document.getElementById("upload").addEventListener("submit", function (event) {
    if (!upload_check()) {
      event.preventDefault(); // stop sending the formular
    }
  });