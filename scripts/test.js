document.addEventListener("DOMContentLoaded", function() {
    const canvas = document.getElementById("table-canvas");
    const ctx = canvas.getContext("2d");
    const addTableButton = document.getElementById("add-table-button");
    const saveLayoutButton = document.getElementById("save-layout-button");
    const loadLayoutButton = document.getElementById("load-layout-button");
    const tableNumberInput = document.getElementById("table-number");
    const tables = [];

    // Initialize the canvas
    function initCanvas() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        drawTables();
    }

    // Draw tables on the canvas
    function drawTables() {
        tables.forEach(table => {
            ctx.fillStyle = "#99cc99";
            ctx.fillRect(table.x, table.y, table.width, table.height);
            ctx.fillStyle = "#000";
            ctx.fillText(`Table ${table.number}`, table.x + 10, table.y + 20);
        });
    }

    // Handle table creation
    addTableButton.addEventListener("click", function() {
        const tableNumber = tableNumberInput.value.trim();
        if (tableNumber) {
            const newTable = {
                number: tableNumber,
                x: 100, // Default x-coordinate
                y: 100, // Default y-coordinate
                width: 80, // Table width
                height: 80  // Table height
            };
            tables.push(newTable);
            initCanvas();
            tableNumberInput.value = "";
        }
    });

    // Save button
    saveLayoutButton.addEventListener("click", function(){
        localStorage.setItem("tableLayout",JSON.stringify(tables));
    });

    // Load Layout
    loadLayoutButton.addEventListener("click",function(){
        const savedLayout = localStorage.getItem("tableLayout");
        if(savedLayout){
            table.length=0;
            const loadedTables = JSON.parse(savedLayout);
            table.push(...loadedTables);
            initCanvas();
        }

    });

    // Handle dragging tables
    let selectedTable = null;
    let offsetX, offsetY;

    canvas.addEventListener("mousedown", function(event) {
        const mouseX = event.clientX - canvas.getBoundingClientRect().left;
        const mouseY = event.clientY - canvas.getBoundingClientRect().top;

        for (let i = tables.length - 1; i >= 0; i--) {
            const table = tables[i];
            if (
                mouseX >= table.x &&
                mouseX <= table.x + table.width &&
                mouseY >= table.y &&
                mouseY <= table.y + table.height
            ) {
                selectedTable = table;
                offsetX = mouseX - table.x;
                offsetY = mouseY - table.y;
                break;
            }
        }
    });

    canvas.addEventListener("mousemove", function(event) {
        if (selectedTable) {
            const mouseX = event.clientX - canvas.getBoundingClientRect().left;
            const mouseY = event.clientY - canvas.getBoundingClientRect().top;

            selectedTable.x = mouseX - offsetX;
            selectedTable.y = mouseY - offsetY;

            initCanvas();
        }
    });

    canvas.addEventListener("mouseup", function() {
        selectedTable = null;
    });

    // Initialize the canvas
    initCanvas();
});
