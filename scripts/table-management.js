let canvas;
const grid = 30;
const backgroundColor = "#52443a";
const lineStroke = "#ebebeb";
const tableFill = "rgba(90, 242, 110, 0.7)";
const tableStroke = "#277031";
const tableShadow = "rgba(0, 0, 0, 0.4) 3px 3px 7px";
var width = 0;
var height = 0;
let canvasEl = document.getElementById("canvas");
const removeTableIds = [];

function sendLinesToBack() {
  canvas.getObjects().map((o) => {
    if (o.type === "line") {
      canvas.sendToBack(o);
    }
  });
}

function initCanvas() {
  if (canvas) {
    canvas.clear();
    canvas.dispose();
  }
  canvas = new fabric.Canvas("canvas");
  canvas.backgroundColor = backgroundColor;
  canvas.setWidth(width || 300);
  canvas.setHeight(height || 300);

  for (let i = 0; i < canvas.height / grid; i++) {
    const lineX = new fabric.Line([0, i * grid, canvas.height, i * grid], {
      selectable: false,
      type: "line",
    });
    const lineY = new fabric.Line([i * grid, 0, i * grid, canvas.height], {
      selectable: false,
      type: "line",
    });
    sendLinesToBack();
    canvas.add(lineX);
    canvas.add(lineY);
  }

  canvas.on("object:moving", function (e) {
    snapToGrid(e.target);
  });

  canvas.on("object:scaling", function (e) {
    if (e.target.scaleX > 5) {
      e.target.scaleX = 5;
    }
    if (e.target.scaleY > 5) {
      e.target.scaleY = 5;
    }
    if (!e.target.strokeWidthUnscaled && e.target.strokeWidth) {
      e.target.strokeWidthUnscaled = e.target.strokeWidth;
    }
    if (e.target.strokeWidthUnscaled) {
      e.target.strokeWidth = e.target.strokeWidthUnscaled / e.target.scaleX;
      if (e.target.strokeWidth === e.target.strokeWidthUnscaled) {
        e.target.strokeWidth = e.target.strokeWidthUnscaled / e.target.scaleY;
      }
    }
  });

  canvas.on("object:modified", function (e) {
    e.target.scaleX =
      e.target.scaleX >= 0.25 ? Math.round(e.target.scaleX * 2) / 2 : 0.5;
    e.target.scaleY =
      e.target.scaleY >= 0.25 ? Math.round(e.target.scaleY * 2) / 2 : 0.5;
    snapToGrid(e.target);
    if (e.target.type === "table") {
      canvas.bringToFront(e.target);
    } else {
      canvas.sendToBack(e.target);
    }
    sendLinesToBack();
  });
  //canvas.observe > canvas.on same function anyway
  canvas.on("object:moving", function (e) {
    checkBoundingBox(e);
  });
  canvas.on("object:rotating", function (e) {
    checkBoundingBox(e);
  });
  canvas.on("object:scaling", function (e) {
    checkBoundingBox(e);
  });
}
//initCanvas();

function resizeCanvas() {
  const newWidth = parseInt(document.getElementById("width").value) || 300;
  const newHeight = parseInt(document.getElementById("height").value) || 300;

  if (canvas) {
    canvas.setWidth(newWidth);
    canvas.setHeight(newHeight);
    width = newWidth; // Update the width variable
    height = newHeight; // Update the height variable
  }
}
resizeCanvas();
function generateId() {
  // return Math.random().toString(36).substr(2, 8);
  const length = 8;
  let result = "";

  for (let i = 0; i < length; i++) {
    const randomDigit = Math.floor(Math.random() * 10);
    result += randomDigit;
  }
  return result;
}

function addRect(left, top, width, height, tableNo, status, tableId, shape) {
  const id = tableId || generateId();
  const fillColor = status === "available" ? "#36d399" : "#f87272";
  console.log(
    "Adding rectangle with parameters:",
    left,
    top,
    width,
    height,
    tableNo,
    tableId,
    status
  );
  const o = new fabric.Rect({
    width: width,
    height: height,
    //fill: tableFill,
    fill: fillColor,
    stroke: tableStroke,
    strokeWidth: 2,
    shadow: tableShadow,
    originX: "center",
    originY: "center",
    centeredRotation: true,
    snapAngle: 45,
    selectable: true,
  });
  const t = new fabric.IText(tableNo.toString(), {
    fontFamily: "sans-serif",
    fontSize: 14,
    fill: "#fff",
    textAlign: "center",
    originX: "center",
    originY: "center",
  });
  const g = new fabric.Group([o, t], {
    left: left,
    top: top,
    centeredRotation: true,
    snapAngle: 45,
    selectable: true,
    type: "rectangle-table",
    id: id,
    number: tableNo,
    shape: "rect",
  });
  //console.log(number); //check connection of js
  canvas.add(g);
  return g;
}

function addCircle(left, top, radius, tableNo, status, tableId, shape) {
  const id = tableId || generateId();
  const fillColor = status === "available" ? "#36d399" : "#f87272";
  console.log(
    "Adding circle with parameters:",
    left,
    top,
    radius,
    tableNo,
    tableId,
    status
  );
  const o = new fabric.Circle({
    radius: radius,
    fill: fillColor,
    stroke: tableStroke,
    strokeWidth: 2,
    shadow: tableShadow,
    originX: "center",
    originY: "center",
    centeredRotation: true,
  });
  const t = new fabric.IText(tableNo.toString(), {
    fontFamily: "sans-serif",
    fontSize: 14,
    fill: "#fff",
    textAlign: "center",
    originX: "center",
    originY: "center",
  });
  const g = tableNo
    ? new fabric.Group([o, t], {
        left: left,
        top: top,
        centeredRotation: true,
        snapAngle: 45,
        selectable: true,
        type: "circle-table",
        id: id,
        number: tableNo,
        shape: "circle",
        radius: radius,
      })
    : null;
  canvas.add(g);
  return g;
}

function snapToGrid(target) {
  target.set({
    left: (Math.round(target.left / (grid / 2)) * grid) / 2,
    top: (Math.round(target.top / (grid / 2)) * grid) / 2,
  });
}

document
  .querySelectorAll(".rectangle")[0]
  .addEventListener("click", function () {
    const tableNo = document.getElementById("table_no").value;
    if (tableNo.trim() !== "") {
      if (isTableNumberExists(tableNo)) {
        alert("Table with the same number already exists");
      } else {
        const o = addRect(0, 0, 60, 60, tableNo, "available");
        canvas.setActiveObject(o);
      }
    } else {
      alert("Please enter the table number");
    }
  });

document.querySelectorAll(".circle")[0].addEventListener("click", function () {
  const tableNo = document.getElementById("table_no").value;
  if (tableNo.trim() !== "") {
    if (isTableNumberExists(tableNo)) {
      alert("Table with the same number already exists");
    } else {
      const o = addCircle(0, 0, 30, tableNo, "available");
      canvas.setActiveObject(o);
    }
  } else {
    alert("Please enter the table number");
  }
});

function checkBoundingBox(e) {
  const obj = e.target;

  if (!obj) {
    return;
  }
  obj.setCoords();

  const objBoundingBox = obj.getBoundingRect();
  if (objBoundingBox.top < 0) {
    obj.set("top", 0);
    obj.setCoords();
  }
  if (objBoundingBox.left > canvas.width - objBoundingBox.width) {
    obj.set("left", canvas.width - objBoundingBox.width);
    obj.setCoords();
  }
  if (objBoundingBox.top > canvas.height - objBoundingBox.height) {
    obj.set("top", canvas.height - objBoundingBox.height);
    obj.setCoords();
  }
  if (objBoundingBox.left < 0) {
    obj.set("left", 0);
    obj.setCoords();
  }
}

document.querySelectorAll(".remove")[0].addEventListener("click", function () {
  removeTable();
});

function removeTable() {
  const o = canvas.getActiveObject();
  if (o) {
    const tableId = o.id;
    removeTableIds.push(tableId);
    o.remove();
    canvas.remove(o);
    canvas.discardActiveObject();
    canvas.renderAll();

    fetch("./CRUD-remove-table-functions.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({ tableId: tableId }),
    })
      .then(function (response) {
        console.log("Table with ID " + tableId + " removed from the database.");
      })
      .catch(function (error) {
        console.error("Error removing table from the database:", error);
      });
  }
}

function saveTableData() {
  // Create an array to store table data
  const tableData = [];
  // Iterate through canvas objects and collect data for tables
  canvas.getObjects().forEach(function (obj) {
    if (obj.type === "rectangle-table") {
      const width = obj.width * obj.scaleX;
      const height = obj.height * obj.scaleY;
      const data = {
        left: obj.left,
        top: obj.top,
        table_shape: obj.get("shape"), // Add the shape property to your table object
        width: width,
        height: height,
        radius: 0,
        number: obj.number,
        table_id: obj.id,
      };
      tableData.push(data);
    } else if (obj.type === "circle-table") {
      const data = {
        left: obj.left,
        top: obj.top,
        table_shape: obj.get("shape"), // Add the shape property to your table object
        radius: obj.radius,
        number: obj.number,
        height: 0,
        width: 0,
        table_id: obj.id,
      };
      tableData.push(data);
    }

    console.log(JSON.stringify(tableData, null, 2));
    //(data, placeholder for replacer allow filter or transform data, number of spaces to use for pretty printing makes it readable)
  });
  // debug check table
  // Send the table data to the server using AJAX (e.g., with the fetch API)

  fetch("./CRUD-table-functions.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify(tableData),
  })
    .then(function (response) {
      // Handle the server's response if needed
      console.log("Layout saved successfully!");
      showSuccessAlert(); // Show the success alert
    })
    .catch(function (error) {
      console.error("Error saving table data:", error);
      showErrorAlert();
    });
}

function showSuccessAlert() {
  const successAlert = document.getElementById("success-alert");
  successAlert.style.display = "flex"; // Show the success alert
  setTimeout(function () {
    successAlert.style.display = "none"; // Hide the success alert after a few seconds
  }, 5000); // time in milliseconds as needed
}

//save
document.querySelector(".save-layout").addEventListener("click", function () {
  saveCanvasProperties();
  saveTableData();
  //saveRemovedTables();
});

//function for existing table number
function isTableNumberExists(tableNo) {
  const existingTables = canvas.getObjects().filter((obj) => {
    return obj.type === "rectangle-table" || obj.type === "circle-table";
  });

  return existingTables.some((table) => table.number === tableNo);
}

//When user press apply the width will change
document.querySelector(".apply-button").addEventListener("click", function () {
  resizeCanvas();
  initCanvas();
  saveCanvasProperties();
  retrieveTableData();
});

function saveCanvasProperties() {
  const canvasWidth = canvas.getWidth();
  const canvasHeight = canvas.getHeight();

  const canvasData = {
    width: canvasWidth,
    height: canvasHeight,
  };

  fetch("./CRUD-layout-functions.php", {
    method: "POST",

    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify(canvasData),
  })
    .then(function (response) {
      // Handle the server's response if needed
      console.log("Canvas properties saved successfully!");
    })
    .catch(function (error) {
      console.error("Error saving canvas properties:", error);
    });
}

function retrieveTableData() {
  //canvas.clear();
  fetch("./CRUD-load-tables.php")
    .then(function (response) {
      if (!response.ok) {
        throw new Error("Network response was not ok");
      }
      return response.json();
    })
    .then(function (data) {
      console.log(data);
      data.forEach(function (table) {
        if (table.table_shape === "rect") {
          console.log(table.table_x);
          const table_x = parseInt(table.table_x);
          const table_y = parseInt(table.table_y);
          const width = parseInt(table.width);
          const height = parseInt(table.height);
          const table_id = parseInt(table.table_id);
          const status = table.table_status;
          addRect(
            table_x,
            table_y,
            width,
            height,
            table.table_no,
            status,
            table_id
          );
          //addRect(30,90,60,90,20);
        } else if (table.table_shape === "circle") {
          console.log(table.table_x);
          const table_x = parseInt(table.table_x);
          const table_y = parseInt(table.table_y);
          const radius = parseInt(table.radius);
          const table_id = parseInt(table.table_id);
          const status = table.table_status;
          addCircle(table_x, table_y, radius, table.table_no, status, table_id);
          //addCircle(30,90,30,10);
        } else {
          console.log("error");
        }
      });
    })
    .catch(function (error) {
      console.error("Error retrieving table data:", error);
    });
}
function hideSuccessAlert() {
  const successAlert = document.getElementById("success-alert");
  successAlert.style.display = "none"; // Hide the success alert on page load
}

document.addEventListener("DOMContentLoaded", function () {
  // Make an AJAX request to retrieve data from the PHP script
  hideSuccessAlert();
  var xhr = new XMLHttpRequest();
  //variable
  var data = [];

  xhr.onreadystatechange = function () {
    if (xhr.readyState === 4 && xhr.status === 200) {
      console.log(xhr.responseText); // debug

      // Parse the JSON response and print it to the console
      data = JSON.parse(xhr.responseText);
      console.log(data);

      if (data.length > 0) {
        width = data[0].width;
        height = data[0].height;
        console.log("Width from the database: " + width);
        console.log("Height from the database: " + height);
        document.getElementById("width").value = width;
        document.getElementById("height").value = height;
        resizeCanvas();
        initCanvas();
        retrieveTableData();
      } else {
        console.log("No data found.");
      }
    }
  };
  xhr.open("GET", "CRUD-load-layout.php", true);
  xhr.send();
});

function addSavedObjects() {
  canvas.getObjects().forEach(function (obj) {
    if (obj.type === "rectangle-table" || obj.type === "circle-table") {
      canvas.remove(obj);
    }
  });
  retrieveTableData();
}
