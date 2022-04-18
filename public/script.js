/*
*  Form helper functions
*/

function searchAutoComplete() {
  var url = "/googleAutoComplete";
  var query = document.getElementById("appendedInputButton").value;

  $.post(url, {q:query}, function(response){

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
  console.log(event.keyCode);
  if (event.keyCode === 13) {
    event.preventDefault();
    googleSearch();
  } else {
    //Run the autocomplete function for each character typed in the search
    //searchAutoComplete();
  }
});

/*

//autocomplete
var bestPictures = new Bloodhound({
  datumTokenizer: Bloodhound.tokenizers.obj.whitespace('value'),
  queryTokenizer: Bloodhound.tokenizers.whitespace,
  //prefetch: '../data/films/post_1960.json',
  remote: {
    url: '../data/films/queries/%QUERY.json',
    wildcard: '%QUERY'
  }
});

$('#appendedInputButton').typeahead(null, {
  name: 'best-pictures',
  display: 'value',
  source: bestPictures
});

*/
