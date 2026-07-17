function updateClock(){

const now = new Date();

let h = now.getHours();

let m = now.getMinutes();

let s = now.getSeconds();

h = h < 10 ? "0"+h : h;

m = m < 10 ? "0"+m : m;

s = s < 10 ? "0"+s : s;

document.getElementById("live-clock").innerHTML = h+":"+m+":"+s;

}

setInterval(updateClock,1000);

updateClock();