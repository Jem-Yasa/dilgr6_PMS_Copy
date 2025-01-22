`use strict`;

function refreshTime() {
    const dayDisplay = document.getElementById("day");
  const timeDisplay = document.getElementById("time");
  const dateString = new Date().toLocaleString("en-US", {timeZone: "Asia/Manila"})
  const formattedString = dateString.replace(", ", " - ");
  const day = formattedString.split('-');
  timeDisplay.textContent = day[1];

  const myArray = day[0].split("/");
  let month ="";
  if(myArray[0] == 1){
    month ="January ";
  } else if(myArray[0] == 2){
    month ="February ";
  }else if(myArray[0] == 3){
    month ="March ";
  }else if(myArray[0] == 4){
    month ="April ";
  }
  else if(myArray[0] == 5){
    month ="May ";
  }else if(myArray[0] == 6){
    month ="June ";
  }else if(myArray[0] == 7){
    month ="July ";
  }else if(myArray[0] == 8){
    month ="August ";
  }else if(myArray[0] == 9){
    month ="September ";
  }else if(myArray[0] == 10){
    month ="October ";
  }else if(myArray[0] == 11){
    month ="November ";
  }else if(myArray[0] == 12){
    month ="December ";
  }
   
  let d="";
  const dayinweek = new Date().getDay();
  if(dayinweek == 1){
    d ="Monday";
  } else if(dayinweek == 2){
    d ="Tuesday";
  }else if(dayinweek == 3){
    d ="Wednesday";
  }else if(dayinweek == 4){ 
    d ="Thursday";
  }else if(dayinweek == 5){
    d ="Friday";
  }else if(dayinweek == 6){
    d ="Saturday";
  }else {
    d ="Sunday";
  }

  const finalDay = d +  ", " + month + myArray[1]+ ", "+ myArray[2];
  console.log(finalDay);
  dayDisplay.textContent = finalDay;
}
  setInterval(refreshTime, 1000);
