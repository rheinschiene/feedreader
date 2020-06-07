if (!window.indexedDB) {
	window.alert("Ihr Browser unterstützt keine stabile Version von IndexedDB. Dieses und jenes Feature wird Ihnen nicht zur Verfügung stehen.");
}
		
// This is what our customer data looks like.
const customerData = [
	{ id: 5, headline: "1", teaser: "middle ", content: "long...", source: "Spiegel", url: "https://spiegel.de", date: "2019-01-01 12:00:00", active: 1 },
	{ id: 7, headline: "2", teaser: "Donna", content: "Story...", source: "Spiegel", url: "https://spiegel.de", date: "2019-01-01 12:00:00", active: 1 },
	{ id: 9, headline: "3", teaser: "Donna", content: "Story...", source: "Spiegel", url: "https://spiegel.de", date: "2019-01-01 12:00:00", active: 1 },
	{ id: 10, headline: "4", teaser: "Donna", content: "Story...", source: "Spiegel", url: "https://spiegel.de", date: "2019-01-01 12:00:00", active: 1 },
	{ id: 11, headline: "5", teaser: "Donna", content: "Story...", source: "Spiegel", url: "https://spiegel.de", date: "2019-01-01 12:00:00", active: 1 },
	{ id: 12, headline: "6", teaser: "Bella", content: "Story...", source: "Spiegel", url: "https://spiegel.de", date: "2019-01-01 12:00:00", active: 1 },
	//{ id: 13, headline: "7", teaser: "Test", content: "Story...", source: "Spiegel", url: "https://spiegel.de", date: "2019-01-01 12:00:00", active: 1 },
];
		
const dbName = "the_name_1";
var db;
var allRecords = 0;
var activeRecords = 0;
var canDeleteRecords = 0;
var syncRecords = 0;
//var firstActiveRecord = 0;
var activeID = 0;
var maxID = 0;
var syncArray = new Array;
var throttle = 0;
		
function open() {
	console.log("openDb ...");
	var request = indexedDB.open(dbName, 1);
		
	request.onerror = function(event) {
		// Handle errors.
		console.log("...error!");
	};
		
	request.onupgradeneeded = function(event) {
		var db = event.target.result;
		var objectStore = db.createObjectStore("feedData", { keyPath: "id" });
		objectStore.createIndex("headline", "headline", { unique: false });
		objectStore.createIndex("teaser", "teaser", { unique: false });
		objectStore.createIndex("content", "content", { unique: false });
		objectStore.createIndex("source", "source", { unique: false });
		objectStore.createIndex("date", "date", { unique: false });
		objectStore.createIndex("active", "active", { unique: false });
		objectStore.createIndex("url", "url", { unique: false });
		objectStore.createIndex("canDelete", "canDelete", { unique: false });
	};
		
	request.onsuccess = function (event) {
		db = this.result;
		update();
		console.log("... DONE");
	};
}

function writedb(data) {
	var transaction = db.transaction(["feedData"], "readwrite");
		
	transaction.oncomplete = function(event) {
		console.log("All done!");
		update(0);
	};
		transaction.onerror = function(event) {
		console.log("Fehler!");
		console.log(event.target.error);
	};
		var objectStore = transaction.objectStore("feedData");
	for (var i in data) {
		var request = objectStore.add(data[i]);
		request.onsuccess = function(event) {
			console.log("geschrieben");
		};
	}
}
	
function update(trigger=1) {		
	// Wie viele Artikel gibt es, wie viele sind aktiv und was ist die höchste ID?
	activeRecords = 0;
	allRecords = 0;
	syncRecords = 0;
	canDeleteRecords = 0;
	maxID = 0;
	var objectStore = db.transaction("feedData").objectStore("feedData");
	objectStore.openCursor(IDBKeyRange.lowerBound(0)).onsuccess = function(event) {
		var cursor = event.target.result;
		if (cursor) {
			// Zählt aktive Artikel
			if(cursor.value.active == 1) {
				activeRecords++;
				if(activeID == 0) {
					activeID = cursor.value.id - 1;
				}
			}
			if(cursor.value.active == 0 && cursor.value.canDelete == 0) {
				syncRecords++;
			}
			// Wie viele Artikel müssen gelöscht werden?
			if(cursor.value.canDelete == 1) {
				canDeleteRecords++
			}
			// Speicher die größte ID
			if(cursor.value.id > maxID) {
				maxID = cursor.value.id;
			}
			// Zählt die Gesamtzahl der Artikel
			allRecords++;
			cursor.continue();
		}
		else {
			$("#allRecords").html(allRecords);
			$("#activeRecords").html(activeRecords);
			$("#syncRecords").html(syncRecords);
			$("#canDeleteRecords").html(canDeleteRecords);

			// Star-Button zurück setzen
			$('#btnStar').removeClass('btn-danger');
			$('#btnStar').addClass('btn-default');
			
			if(trigger) {
				if(syncRecords >= 25) {
					syncContent();
				}
				else if(canDeleteRecords >= 50) {
					deleteOld();
				}
				else if(activeRecords <= 10) {
					if(!(throttle % 10)) {
						loadContent();
					}
					else {
						throttle++;
					}
				}
			}
		}
	};		
}
	
function resetdb() {

	db.close();

	var request = window.indexedDB.deleteDatabase(dbName);

	request.onblocked = function(event) {
		console.log("Error message: Database in blocked state.");
	};
	
	request.onerror = function(event) {
	  console.log("Error deleting database.");
	};
		request.onsuccess = function(event) {
	  console.log("Database deleted successfully");
	};
}

function loadContent() {
	console.log("loadContent: maxID=" + maxID);
	$.get({
		url: '/feed/feed-data/get-content?startID=' + maxID,
		dataType: 'json',
		success: function(data){
			console.log("Ajax-Request-Load: Done");
			if(data.length > 0) {
				console.log("loadContent: Got data, start writing...");
				$("#ajaxStatus").append("<p>loadContent: ok</p>");
				throttle = 0;
				writedb(data);
			}
			else {
				console.log("loadContent: Got no data!");
				$("#ajaxStatus").append("<p>loadContent: no data</p>");
				throttle++;
			}
		},
		error: function(XMLHttpRequest, textStatus, errorThrown){
			console.log(XMLHttpRequest.responseText);
			$("#ajaxStatus").append("<p>loadContent: " + textStatus + " --> " + errorThrown + "</p>");
		},
	});
}

function syncContent() {
	syncArray = Array();
	var objectStore = db.transaction("feedData", 'readonly').objectStore("feedData");
	objectStore.openCursor(IDBKeyRange.lowerBound(0)).onsuccess = function(event) {
		var cursor = event.target.result;
		if (cursor) {
			// Welche Artikel sind schon gelesen?
			
			if(cursor.value.active == 0 && cursor.value.canDelete == 0) {
				syncArray.push(cursor.value.id);
				//cursor.value.canDelete = 1;
				//cursor.update(cursor.value);
				
			}
			cursor.continue();
		}
		else {
			if(!(syncArray.length > 0)) {
				console.log("syncContent: Array empty!");
				return;
			}
			// Alle geprüft... gefundene syncen!
			var counter = 0;
			$.ajax({
				url: '/feed/feed-data/set-inactive',
				type: 'POST',
				data: { syncArray: syncArray },
				dataType: 'json',
				success: function(data){
					$("#ajaxStatus").append("<p>syncContent: ok</p>");
					syncArray.forEach(syncContentSetCanDelete);
					update(0);
				},
				error: function(XMLHttpRequest, textStatus, errorThrown){
					console.log(XMLHttpRequest.responseText);
					$("#ajaxStatus").append("<p>syncContent: " + textStatus + " --> " + errorThrown + "</p>");
					var self = this;
					var retry = function () {
					    $.ajax(self);
					};
					if(counter < 6) {
						counter++;
						setTimeout(retry, 10000);
					}
				},
			});
		}
	};
}

function syncContentSetCanDelete(id, index) {
	var objectStore = db.transaction("feedData", 'readwrite').objectStore("feedData");
	objectStore.openCursor(IDBKeyRange.only(id)).onsuccess = function(event) {
		var cursor = event.target.result;
		if (cursor) {
			cursor.value.canDelete = 1;
			cursor.update(cursor.value);
		}
		else {
			console.log("syncContentSetCanDelete: Kein Item gefunden!");
		}
	};
}

function deleteOld() {
	console.log("deleteOld!");
	var counter = 0;
	var objectStore = db.transaction("feedData", 'readwrite').objectStore("feedData");
	objectStore.openCursor(IDBKeyRange.lowerBound(0)).onsuccess = function(event) {
		var cursor = event.target.result;
		if (cursor) {
			// Welche Artikel können gelöscht werden?
			if(cursor.value.canDelete == 1) {
				cursor.delete();
				counter++;
			}
			// Nur 25 Artikel löschen...
			if(counter < 25) {
				cursor.continue();
			}
			else {
				update(0);
			}
		}
		else {
			// Keine weiteren Artikel...
		}
	};
}
		
$(document).ready(function(){
		
	$("#btnNext").click(function(){
		var objectStore = db.transaction(["feedData"], "readwrite").objectStore("feedData");
		objectStore.openCursor(IDBKeyRange.lowerBound(activeID, true), "next").onsuccess = function(event) {
			var cursor = event.target.result;
			if (cursor) {
				$("#tableHeadline").html("<a href='" + cursor.value.url + "'>" + cursor.value.headline + "</a>");
				$("#tableDate").html(cursor.value.source + " - " + cursor.value.date);
				$("#tableTeaser").html(cursor.value.teaser);
				$("#tableContent").html(cursor.value.content);
				activeID = cursor.value.id;
				
				// Set article to inactive
				if(cursor.value.active == 1) {
					cursor.value.active = 0;
					var request = cursor.update(cursor.value);
				}
			}
			else {
				console.log("No more entries!");
			}
			update();
		};
	});
	
	$("#btnPrevious").click(function(){
		var objectStore = db.transaction("feedData").objectStore("feedData");
		objectStore.openCursor(IDBKeyRange.upperBound(activeID, true), "prev").onsuccess = function(event) {
			var cursor = event.target.result;
			if (cursor) {
				//currentRecord--;
				//$("#currentRecord").html(currentRecord);
				$("#tableHeadline").html("<a href='" + cursor.value.url + "'>" + cursor.value.headline + "</a>");
				$("#tableDate").html(cursor.value.source + " - " + cursor.value.date);
				$("#tableTeaser").html(cursor.value.teaser);
				$("#tableContent").html(cursor.value.content);
				activeID = cursor.value.id;
			}
			else {
				console.log("No more entries!");
			}
		};
	});
	
	$("#btnSync").click(function(){
		console.log("Sync...");
		syncContent();
	});
		$("#btnGet").click(function(){
		loadContent();			
	});
	
	$("#btnReset").click(function(){
		console.log("Resetting DB...");
		resetdb();
	});

	$("#btnStar").click(function(){

		var id = activeID;
		console.log("ID: " + activeID);

		$.get({
			url: '/feed/feed-data/set-star?id=' + id,
			dataType: 'json',
			success: function(data){
				if(data['result'] == 'marked') {
					$('#btnStar').removeClass('btn-default');
					$('#btnStar').addClass('btn-danger');
					console.log("marked");
				}
				else {
					$("#ajaxStatus").append("<p>starArticle: " + data['result'] + "</p>");
				}
			},
			error: function(XMLHttpRequest, textStatus, errorThrown){
				console.log(XMLHttpRequest.responseText);
				$("#ajaxStatus").append("<p>starArticle: " + textStatus + " --> " + errorThrown + "</p>");
			},
		});
	});
	
});
	
open();
