/*
*  Form helper functions
*/

function searchAutoComplete() {
  var url = "/GoogleAutoComplete";
  var query = document.getElementById("appendedInputButton").value;

  $.post(url, {q:query}, function(response){

    console.log(response);

    res_obj = JSON.parse(response);

    console.log(res_obj[1]);
  });

}

function googleSearch() {
  document.getElementById("appendedInputButton").setAttribute("name","q");
  document.getElementById("searchForm").setAttribute("action","https://www.google.com/search");
  document.getElementById("searchForm").submit();
}

function redditSearch() {
  document.getElementById("appendedInputButton").setAttribute("name","q");
  document.getElementById("searchForm").setAttribute("action","https://www.reddit.com/search/");
  document.getElementById("searchForm").submit();
}

function youtubeSearch() {
  document.getElementById("appendedInputButton").setAttribute("name","search_query");
  document.getElementById("searchForm").setAttribute("action","https://www.youtube.com/results");
  document.getElementById("searchForm").submit();
}

function submitNewRecord() {
  document.getElementById("newRecordForm").submit();
}

/*
*   Event Bindings
*/

var searchInput = document.getElementById("appendedInputButton");
searchInput.addEventListener("keyup", function(event) {
  if (event.keyCode === 13) {
    event.preventDefault();

    console.log("Search");
  }
});

//Capture enter key and run Google search
$("#appendedInputButton").keyup(function(event) {
  if (event.keyCode === 13) {
    event.preventDefault();
    googleSearch();
  } else {
    //Run the autocomplete function for each character typed in the search
    //searchAutoComplete();
  }
});

$('#appendedInputButton').autocomplete({
    serviceUrl: '/GoogleAutoComplete',
    onSelect: function (suggestion) {
        $('#appendedInputButton').value = suggestion.value;
        googleSearch()
    }
});
