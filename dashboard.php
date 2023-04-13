<?php
require_once 'config.php';
include_once 'analytics.txt';
require_once 'router.php';

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unlock Reddit</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.13.2/themes/smoothness/jquery-ui.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.13.2/jquery-ui.min.js"></script>
    <link rel="apple-touch-icon" sizes="180x180" href="/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon/favicon-16x16.png">
    <link rel="manifest" href="/favicon/site.webmanifest">
    <style>
        /* Hide scrollbar for Chrome, Safari and Opera */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        /* Hide scrollbar for IE, Edge and Firefox */
        .no-scrollbar {
            -ms-overflow-style: none;
            /* IE and Edge */
            scrollbar-width: none;
            /* Firefox */
        }

        .highlight {
            background: #379bf7;
            padding: 2px;
            color: white;
            border-radius: 4px;
        }


        li>ul {
            transform: translatex(100%) scale(0);
        }

        li:hover>ul {
            transform: translatex(101%) scale(1);
        }

        li>button svg {
            transform: rotate(-90deg);
        }

        li:hover>button svg {
            transform: rotate(-270deg);
        }

        .blink_text {
            animation: blinker 2s linear infinite;
        }

        @keyframes blinker {
            50% {
                opacity: 0;
            }
        }
    </style>
</head>

<body class="bg-gray-100 font-sans no-scrollbar">

    <div class="bg-gray-100 sticky top-0 w-screen shadow-lg">
        <div class="py-3 ml-5 inline-block">
            <h2 class="text-gray-600 text-xl font-medium">Unlock Reddit</span></h2>
        </div>

        <div class="py-3 mr-5 inline-block float-right">

            <img src="<?= $userinfo['picture'] ?>" alt="" class="rounded-full inline" height="24" width="24">
            <a href="logout.php" class="font-bold text-red-600 inline">Logout</a>
        </div>
    </div>


    <div class="bg-gray-100 w-screen">

        <div class="rounded-lg m-2 h-36 bg-slate-600 grid grid-cols-1 gap-2 justify-items-center md:grid-cols-2 md:gap-1 md:h-16 md:w-4/6 md:mx-2 md:mx-auto md:mx-36 lg:h-16 lg:grid-cols-2">

            <div class="mt-8 md:mt-4 flex justify-center md:justify-end lg:justify-center w-full">
                <input id="subreddit_input" class="text-center h-9 w-8/12 p-1 rounded-lg outline-0 sm:w-8/12 md:w-10/12 lg:w-3/5 xl:w-8/12 2xl:w-7/12" type="text" name="" value="" placeholder="Enter the subreddit...">
            </div>

            <div class="mt-2 flex justify-center lg:justify-start md:mt-4 w-full">
                <button id="btn_subreddit_select" class="h-9 bg-yellow-300 hover:bg-yellow-400 hover:font-bold hover:text-black p-1 rounded-lg w-36 sm:w-36 md:w-40 lg:w-44 xl:w-48 2xl:w-52" type="button" name="button">
                    Search
                </button>
            </div>
        </div>

        <div class="rounded-lg m-2 h-6 bg-slate-300 grid grid-cols-1 gap-2 justify-items-center md:w-4/6 md:mx-2 md:mx-auto md:mx-36">
            <a href="https://blog.unlockreddit.com/unlockreddit-blogpost-ideas/" class="blink_text text-center font-bold text-red-500" target="_blank">How to Guide?</a>
        </div>

        <div id="subreddit_post" class="my-4">
        </div>

    </div>


    <script>
        $(document).ready(function() {

            $("#subreddit_post").append(PostPreloader());

            GetSubredditsPost("startups", "?");

            $("#subreddit_input").autocomplete({
                source: function(request, response) {
                    $.ajax({
                        url: "https://www.reddit.com/subreddits/search.json?q=" + request.term,
                        dataType: "json",
                        data: {
                            action: "opensearch",
                            format: "json",
                            namespace: 0,
                            limit: 10,
                        },
                        success: function(data) {
                            console.log(data);
                            var subredditArr = [];
                            for (var i = 0; i < data.data.children.length; i++) {
                                subredditArr.push(data.data.children[i].data.display_name + " (" + ShortNumbers(data.data.children[i].data.subscribers) + ")");
                            }
                            response(subredditArr);
                        },
                    });
                },
            });

            $("#btn_subreddit_select").click(function() {
                $("#subreddit_post").html("");
                $("#subreddit_post").append(PostPreloader());
                var user_selected_subreddit;
                var user_selected_keyword;
                if ($("#subreddit_input").val() == "") {
                    ErrorInputField();
                } else {
                    NoErrorInputField();
                    user_selected_subreddit = $("#subreddit_input").val();
                    user_selected_subreddit_trim = user_selected_subreddit.replace(/ *\([^)]*\) */g, "");

                    GetSubredditsPost(user_selected_subreddit_trim);
                }
            });

            function ShortNumbers(num) {
                num = num.toString().replace(/[^0-9.]/g, '');
                if (num < 1000) {
                    return num;
                }
                let si = [{
                        v: 1E3,
                        s: "K"
                    },
                    {
                        v: 1E6,
                        s: "M"
                    },
                    {
                        v: 1E9,
                        s: "B"
                    },
                    {
                        v: 1E12,
                        s: "T"
                    },
                    {
                        v: 1E15,
                        s: "P"
                    },
                    {
                        v: 1E18,
                        s: "E"
                    }
                ];
                let index;
                for (index = si.length - 1; index > 0; index--) {
                    if (num >= si[index].v) {
                        break;
                    }
                }
                return (num / si[index].v).toFixed(2).replace(/\.0+$|(\.[0-9]*[1-9])0+$/, "$1") + si[index].s;
            }

            function ErrorInputField() {
                $("#subreddit_input").css("background-color", "#FF5C5C");
                $('#subreddit_input').prop('title', '* required');
            }

            function NoErrorInputField() {
                $('#subreddit_input').removeAttr("title");
                $("#subreddit_input").css("background-color", "#ffffff");
            }

            function GetSubredditsPost(subreddit) {
                $.ajax({
                    url: 'https://www.reddit.com/r/' + subreddit + '.json?&limit=100',
                    dataType: 'json',
                    success: function(result) {

                        $("#subreddit_post").html("");

                        if (result.data.children.length > 0) {
                            var subreddit_post_array = [];

                            for (var i = result.data.children.length - 1; i >= 0; i--) {
                                if (result.data.children[i].data.title.includes("?") ||
                                    result.data.children[i].data.title.includes("help") ||
                                    result.data.children[i].data.title.includes("how") ||
                                    result.data.children[i].data.title.includes("hate") ||
                                    result.data.children[i].data.title.includes("worst") ||
                                    result.data.children[i].data.title.includes("sucks") ||
                                    result.data.children[i].data.title.includes("annoying") ||
                                    result.data.children[i].data.title.includes("struggling") ||
                                    result.data.children[i].data.title.includes("tips") ||
                                    result.data.children[i].data.title.includes("suggest") ||
                                    result.data.children[i].data.title.includes("any tools") ||
                                    result.data.children[i].data.title.includes("any apps") ||
                                    result.data.children[i].data.title.includes("any website") ||
                                    result.data.children[i].data.title.includes("any sites") ||
                                    result.data.children[i].data.title.includes("any software") ||
                                    result.data.children[i].data.title.includes("problem")
                                ) {
                                    subreddit_post_array.push(i);
                                    $("#subreddit_post").append("<div class=\"md:w-4/6 mx-2 md:mx-auto px-4 py-4 bg-gray-100 border border-gray-250 hover:border-gray-400 hover:font-semibold\"><h2 class=\"text-gray-600\"><a href=" + result.data.children[i].data.url + " target=\"_blank\">" + result.data.children[i].data.title + "</a></h2></div>");
                                }
                            }

                            if (!subreddit_post_array.length > 0) {
                                $("#subreddit_post").append(ShowNoData);
                            }

                            HighlightSearchKeywords();

                        } else {
                            $("#subreddit_post").append(ShowNoData);
                        }
                    }
                });
            }

            function PostPreloader() {
                var postPreloaderHtml = '';

                for (i = 0; i < 10; i++) {
                    postPreloaderHtml += '<div class=\"md:w-4/6 mx-2 md:mx-auto border border-gray-300 p-4 my-1\"><div class=\"animate-pulse flex space-x-4\"><div class=\"flex-1 space-y-2 py-1\"><div class=\"h-2 bg-slate-200\"></div><div class=\"space-y-3\"><div class=\"h-2 bg-slate-200\"></div></div></div></div></div>';
                }

                return postPreloaderHtml;
            }

            function HighlightSearchKeywords() {
                $("#subreddit_post").find(".highlight").removeClass("highlight");

                var search_keywords = [
                    "help", "how",
                    "hate", "worst",
                    "sucks", "annoying",
                    "struggling", "tips",
                    "suggest", "any tools",
                    "any apps", "any website",
                    "any sites", "any software",
                    "problem"
                ]

                var searchword;
                for (let i = 0; i < search_keywords.length; i++) {
                    searchword = search_keywords[i];

                    var custfilter = new RegExp(searchword, "ig");

                    var repstr = "<span class='highlight'>" + searchword + "</span>";

                    if (searchword != "") {
                        $('#subreddit_post').each(function() {
                            $(this).html($(this).html().replace(custfilter, repstr));
                        })
                    }
                }


            }

            function ShowNoData() {
                var noDataHtml = '<div class=\"pt-48\"><img class=\"mx-auto\" src=\"images/folder.png\" alt=\"no-data\"><h2 class=\"text-center font-semibold text-gray-600\">No Data!</h2></div>';
                return noDataHtml;
            }

            //jquery end
        });
    </script>
</body>

</html>