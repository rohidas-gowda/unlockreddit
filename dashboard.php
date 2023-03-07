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
            background: #4b5563;
            padding: 2px;
            color: white;
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
    </style>
</head>

<body class="bg-gray-100 font-sans no-scrollbar">

    <div class="bg-gray-100 sticky top-0 w-screen">
        <div class="py-3 ml-5 inline-block">
            <h2 class="text-gray-600 text-xl font-medium">Unlock Reddit</h2>
        </div>

        <div class="py-3 mr-5 inline-block float-right">
            <img src="<?= $userinfo['picture'] ?>" alt="" class="rounded-full inline" height="24" width="24">
            <a href="logout.php" class="font-bold text-red-600 inline">Logout</a>
        </div>
    </div>


    <div class="bg-gray-100 w-screen my-4">

        <div class="m-2 h-72 bg-gray-800 grid grid-cols-1 gap-2 justify-items-center md:grid-cols-2 md:gap-1 md:h-36 lg:grid-cols-4 lg:h-24">

        <div class="mt-8">
            <input id="subreddit_input" class="text-center w-72 p-1 rounded-lg outline-0 sm:w-96 md:w-64 lg:w-56 xl:w-72 2xl:w-80" type="text" name="" value="" placeholder="Enter the subreddit...">
        </div>

        <div class="mt-3 md:mt-8">
            <select id="select_keyword_category" class="w-72 p-1 rounded-lg outline-0 sm:w-96 md:w-64 lg:w-56 xl:w-72 2xl:w-80 text-center">
                <option value="questions" selected>Ask</option>
                <option value="advice">Need Advice</option>
                <option value="pain">Pain & Anger</option>
                <option value="solution">Need Solution</option>
            </select>
        </div>

        <div class="mt-3 md:mt-0 lg:mt-8">
            <select id="select_search_keywords" class="w-72 p-1 rounded-lg outline-0 sm:w-96 md:w-64 lg:w-56 xl:w-72 2xl:w-80 text-center">
            </select>
        </div>

        <div class="mt-2 lg:mt-8">
            <button id="btn_subreddit_select" class="bg-yellow-300 hover:bg-green-500 hover:font-bold hover:text-white p-1 rounded-lg w-24 sm:w-36 md:w-40 lg:w-44 xl:w-48 2xl:w-52" type="button" name="button">
                Search
            </button>
        </div>    
        </div>

        <div id="subreddit_post" class="my-16">
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

            $('#select_search_keywords').append(QuestionsOptions());

            $('#select_keyword_category').change(function() {
                var keyword_category = $('#select_keyword_category').val();
                $('#select_search_keywords').html('');
                switch (keyword_category) {
                    case 'questions':
                        $('#select_search_keywords').append(QuestionsOptions());
                        break;

                    case 'advice':
                        $('#select_search_keywords').append(AdviceOptions());
                        break;

                    case 'pain':
                        $('#select_search_keywords').append(PainOptions());
                        break;

                    case 'solution':
                        $('#select_search_keywords').append(SolutionOptions());
                        break;

                    default:
                        $('#select_search_keywords').append(QuestionsOptions());
                        break;
                }
            });

            function QuestionsOptions() {
                var questionRequest = '<option value=\'?\'>Questions</option>';
                return questionRequest;
            }

            function AdviceOptions() {
                var adviceRequest;
                adviceRequest += '<option value=\'how to\'>How to...</option>';
                adviceRequest += '<option value=\'help\'>Help...</option>';
                adviceRequest += '<option value=\'tips\'>Tips...</option>';
                return adviceRequest;
            }

            function PainOptions() {
                var painAndAnger;
                painAndAnger += '<option value=\'i hate\'>I hate...</option>';
                painAndAnger += '<option value=\'this sucks\'>This sucks...</option>';
                painAndAnger += '<option value=\'i\'m tired\'>I\'m tired...</option>';
                painAndAnger += '<option value=\'how frustrating\'>How frustrating...</option>';
                return painAndAnger;
            }

            function SolutionOptions() {
                var solutionRequest;
                solutionRequest += '<option value=\'any tools\'>Any tools...</option>';
                solutionRequest += '<option value=\'i wish\'>I wish...</option>';
                solutionRequest += '<option value=\'any app\'>Any apps...</option>';
                solutionRequest += '<option value=\'any websites\'>Any websites...</option>';
                solutionRequest += '<option value=\'any solution\'>Any solutions...</option>';
                solutionRequest += '<option value=\'any sites\'>Any sites...</option>';
                return solutionRequest;
            }

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
                    user_selected_keyword = $("#select_search_keywords").find(":selected").val();
                    user_selected_subreddit_trim = user_selected_subreddit.replace(/ *\([^)]*\) */g, "");

                    GetSubredditsPost(user_selected_subreddit_trim, user_selected_keyword);
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

            function GetSubredditsPost(subreddit, search_keyword) {
                $.ajax({
                    url: 'https://www.reddit.com/r/' + subreddit + '.json?&limit=100',
                    dataType: 'json',
                    success: function(result) {

                        $("#subreddit_post").html("");

                        if (result.data.children.length > 0) {
                            var subreddit_post_array = [];

                            for (var i = result.data.children.length - 1; i >= 0; i--) {
                                if (result.data.children[i].data.title.includes(search_keyword)) {
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
                if ($("#select_search_keywords").val() != "?") {

                    $("#subreddit_post").find(".highlight").removeClass("highlight");

                    var searchword = $("#select_search_keywords").val();

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