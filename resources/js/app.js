require("./bootstrap");

import Alpine from "alpinejs";

window.Alpine = Alpine;

Alpine.start();

$(document).ready(function () {
    if ($(".msg_card_body:first").length) {
        var scrollheight = $(".msg_card_body:first")[0].scrollHeight;
        $(".msg_card_body:first").scrollTop(scrollheight);
    }

    $("#action_menu_btn").click(function () {
        $(".action_menu").toggle();
    });

    $("#end_game_button").click(function () {
        if (confirm("Do you want to end the game ?") == true) {
        } else {
            return 0;
        }

        var current_game_id = $("#current_game_id").val();
        console.log(current_game_id);
        $.ajax({
            type: "POST",
            url: "/game/end",
            data: { id: current_game_id },
            success: function (data) {
                //    alert(data.success);
                location.reload();
            },
        });
    });

    $("#new_game_button").click(function () {
        if (confirm("Do you want to create a new game ?") == true) {
        } else {
            return 0;
        }

        $.ajax({
            type: "POST",
            url: "/game/new",
            data: {},
            success: function (data) {
                //    alert(data.success);
                if (data.success) {
                    //   location.href("/game/"+data.new_game_id);
                    window.location.href = "/game/" + data.new_game_id;
                }
            },
        });
    });

    $("#user_guess").keydown(function (event) {
        var id = event.key || event.which || event.keyCode || 0;
        // console.log("id", id);
        if (id == "Enter") {
            $("#send_guess_button").trigger("click");
        }
    });

    $("#send_guess_button").click(function () {
        var current_game_id = $("#current_game_id").val();
        console.log(current_game_id);
        var user_guess = $("#user_guess").val();
        console.log(user_guess);

        $.ajax({
            type: "POST",
            url: "/game/" + current_game_id + "/guess",
            data: {
                current_game_id: current_game_id,
                user_guess: user_guess,
            },
            success: function (data) {
                console.log("data", data);

                if (data.success) {
                    window.location.href = "/game/" + data.current_game.id;
                } else {
                    alert(data.response_sentence);
                }
            },
        });
    });
});
