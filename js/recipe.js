let showratingbox=()=>{
    if(document.getElementById("ratingBox").style.display =="block")
    {
       document.getElementById("ratingBox").style.display ="none";
    }
    else{

       document.getElementById("ratingBox").style.display ="block";
    }

}


// Optional: Close modal when clicking outside the modal content
window.onclick = function(event) {
   const modal = document.getElementById("commentModal1");
   if (event.target === modal) {
       modal.style.display = "none";
   }
}

