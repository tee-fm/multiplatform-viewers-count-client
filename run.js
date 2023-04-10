const {exec} = require("child_process");

runArtisan();
setInterval(function () {
    runArtisan();
}, 60 * 1000);

function runArtisan() {
    var options = {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: 'numeric',
        minute: 'numeric',
        second: 'numeric'
    };
    var today = new Date();
    console.log(today.toLocaleDateString("en-GB", options));

    exec("php artisan stats:fetch", (error, stdout, stderr) => {
        if (error) {
            console.log(`error: ${error.message}`);
            return 0;
        }
        if (stderr) {
            console.log(`stderr: ${stderr}`);
            return 0;
        }
        console.log(`stdout: ${stdout}`);
        return 0;
    });
}
