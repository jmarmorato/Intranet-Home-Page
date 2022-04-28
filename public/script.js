/*
*  Form submission functions
*/

function googleSearch() {
  $("#appendedInputButton").attr("name","q");
  $("#searchForm").attr("action","https://www.google.com/search");
  $("#searchForm")[0].submit();
}

function redditSearch() {
  $("#appendedInputButton").attr("name","q");
  $("#searchForm").attr("action","https://www.reddit.com/search/");
  $("#searchForm")[0].submit();
}

function youtubeSearch() {
  $("#appendedInputButton").attr("name","search_query");
  $("#searchForm").attr("action","https://www.youtube.com/results");
  $("#searchForm")[0].submit();
}

function submitNewRecord() {
  $("#newRecordForm")[0].submit();
}

/*
*   Event Bindings
*/

//Capture enter key and run Google search
$("#appendedInputButton").keyup(function(event) {
  if (event.keyCode === 13) {
    event.preventDefault();
    googleSearch();
  }
});

//Attach autocomplete
$('#appendedInputButton').autocomplete({
  serviceUrl: '/gacp.php',
  onSelect: function (suggestion) {
    $('#appendedInputButton').value = suggestion.value;
    googleSearch()
  }
});
