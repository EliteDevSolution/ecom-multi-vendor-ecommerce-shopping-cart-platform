/**--------------------------------
 * Set interval for all countdown
 * -----------------------------**/
setInterval(function(){
    x();
    home6();
    homeSixTwo();
},1000);

var countDownDate = new Date("Dec 15, 2018 23:37:25").getTime();
/**-------------------------------
 *  home six countdown area two
 * ----------------------------**/
var homeSixTwo = function(){

    var data = countdownstart(countDownDate);
    
    var hours = document.getElementById('cwhour');
    var miniutes = document.getElementById('cwmin');
    var seconds = document.getElementById('cwsec');

    if( hours || miniutes){

        hours.innerHTML = data.hours;
        miniutes.innerHTML = data.minutes;
        seconds.innerHTML = data.seconds;

        if (data.distance < 0) {
            clearInterval(homeSixTwo);
            hours.innerHTML = '00';
            miniutes.innerHTML = '00';
            seconds.innerHTML = '00';
        }

    }
}

/**-------------------------------
 *  home six countdown area One
 * ----------------------------**/
var home6 = function(){

    var data = countdownstart(countDownDate);

    var hours = document.getElementById('chour');
    var miniutes = document.getElementById('cmin');
    var seconds = document.getElementById('csec');

    if( hours || miniutes){

        hours.innerHTML = data.hours;
        miniutes.innerHTML = data.minutes;
        seconds.innerHTML = data.seconds;

        if (data.distance < 0) {
            clearInterval(home6);
            hours.innerHTML = '00';
            miniutes.innerHTML = '00';
            seconds.innerHTML = '00';
        }

    }

};
/**-------------------------------
 *  home one countdown area  * ----------------------------**/
var x = function(){

    var data = countdownstart(countDownDate);

    var days = document.getElementById('days');
    var hours = document.getElementById('hours');
    var miniutes = document.getElementById('miniutes');
    var seconds = document.getElementById('seconds');

    if( days || hours){
        days.innerHTML = data.days;
        hours.innerHTML = data.hours;
        miniutes.innerHTML = data.minutes;
        seconds.innerHTML = data.seconds;

        if (data.distance < 0) {
            clearInterval(x);
            days.innerHTML = '00';
            hours.innerHTML = '00';
            miniutes.innerHTML = '00';
            seconds.innerHTML = '00';
        }
    }
    
};



function countdownstart(distance){
    //get today date time
    var now = new Date().getTime();

    //get the distance
    var distance = countDownDate - now;

    var days = Math.floor( distance / ( 1000 * 60 * 60 *24 ));
    var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    var seconds = Math.floor((distance % (1000 * 60)) / 1000);
    return ({
        "days":days,
        "hours" : hours,
        "minutes" : minutes,
        "seconds" : seconds,
        "distance" : distance
    });
}

