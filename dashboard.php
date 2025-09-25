<?php
require('db.php');
include("auth.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Live Investment Dashboard</title>
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body {
      font-family: 'Segoe UI', Tahoma, sans-serif;
      background: linear-gradient(135deg, rgba(11,27,103,0.9) 0%, rgba(40,60,134,0.9) 100%);
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: start;
      padding: 20px;
    }
    .container {
      width: 100%;
      max-width: 1200px;
      background: rgba(255,255,255,0.95);
      border-radius: 16px;
      box-shadow: 0 15px 35px rgba(0,0,0,0.15);
      padding: 30px;
      animation: fadeIn 1s ease;
    }
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }
    h1 {
      text-align: center;
      color: #333;
      font-size: 2.5rem;
      letter-spacing: 1px;
      margin-bottom: 25px;
    }
    .balance-card {
      background: linear-gradient(135deg, #00c6ff, #0072ff);
      padding: 25px;
      border-radius: 12px;
      color: #fff;
      text-align: center;
      box-shadow: 0 8px 20px rgba(0,0,0,0.2);
      margin-bottom: 30px;
      transition: transform 0.3s ease;
    }
    .balance-card:hover {
      transform: translateY(-5px);
    }
    .balance-card h2 {
      font-size: 1.5rem;
      font-weight: 600;
    }
    .balance-amount {
      font-size: 3rem;
      margin-top: 10px;
      font-weight: bold;
    }
    .table-wrapper {
      overflow-x: auto;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      background: white;
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    th, td {
      padding: 16px;
      text-align: left;
      border-bottom: 1px solid #eee;
    }
    th {
      background: #f0f4f7;
      font-weight: 600;
      color: #444;
    }
    tr:hover {
      background: #f9fcff;
      transition: background 0.3s ease;
    }
    .positive { color: #27ae60; font-weight: 600; }
    .negative { color: #e74c3c; font-weight: 600; }
    .actions {
      text-align: center;
      margin-top: 25px;
    }
    .btn {
      padding: 12px 28px;
      margin: 10px;
      border: none;
      border-radius: 25px;
      font-size: 16px;
      cursor: pointer;
      transition: all 0.3s ease;
    }
    .deposit {
      background: linear-gradient(135deg, rgba(11,27,103,0.9) 0%, rgba(40,60,134,0.9) 100%);
      color: white;
    }
    .withdraw {
      background: linear-gradient(135deg, #d3cce3, #e9e4f0);
      color: #333;
    }
    .btn:hover {
      transform: scale(1.05);
      opacity: 0.9;
    }

    @media (max-width: 600px) {
      .balance-amount { font-size: 2.2rem; }
      h1 { font-size: 2rem; }
    }
  </style>
</head>
<body>

  <div class="container">
    <h1> Live Investment Dashboard</h1>

    <div class="balance-card">
      <h2>Account Balance</h2>
      <div class="balance-amount" id="balance">$ <?php echo $_SESSION['profits']; ?> </h1></div>
    </div>

    <div class="table-wrapper">
      <table>
        <thead>
          <tr>
            <th>Asset</th>
            <th>Type</th>
            <th>Quantity</th>
            <th>Current Price</th>
            <th>Total Value</th>
            <th>Change (24h)</th>
          </tr>
        </thead>
        <tbody id="assets-table">
          <!-- rows will go here -->
        </tbody>
      </table>
    </div>

    <div class="actions">
     <button class="btn deposit" onclick="alert('Deposit not implemented yet')">💰 Deposit</button>
      <button class="btn withdraw" onclick="alert('Withdraw not implemented yet')">💸 Withdraw</button>
    </div>

  </div>
<script defer src="https://www.livecoinwatch.com/static/lcw-widget.js"></script> 
  
  <script>
  let assets = [
    { symbol: "bitcoin", name: "Bitcoin", type: "Crypto", quantity: <?php echo $_SESSION['btc']; ?>, price: 0, change: 0 },
    { symbol: "ethereum", name: "Ethereum", type: "Crypto", quantity: <?php echo $_SESSION['eth']; ?>, price: 0, change: 0 },
    { symbol: "dogecoin", name: "Dogecoin", type: "Crypto", quantity: <?php echo $_SESSION['dodge']; ?>, price: 0, change: 0 },
    { symbol: "USDT", name: "USDT", type: "Crypto", quantity: <?php echo $_SESSION['usdt']; ?>, price: 0, change: 0 },
    { symbol: "VOO", name: "Vanguard S&P 500 ETF", type: "ETF", quantity: <?php echo $_SESSION['usdt']; ?>, price: 0, change: 0 }
  ];

  function renderDashboard() {
  const tbody = document.getElementById("assets-table");
  tbody.innerHTML = "";
  assets.forEach(a => {
    const totalValue = a.price * a.quantity;
    const priceDisplay = a.price > 0
      ? `$${a.price.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})}`
      : "-";
    const totalValueDisplay = a.price > 0
      ? `$${totalValue.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})}`
      : "-";
    const changeDisplay = (typeof a.change === "number")
      ? (a.change >= 0 ? "+" : "") + a.change.toFixed(2) + "%"
      : "0.00%";
    const changeClass = a.change >= 0 ? "positive" : "negative";

    const tr = document.createElement("tr");
    tr.innerHTML = `
      <td>${a.name} (${a.symbol})</td>
      <td>${a.type}</td>
      <td>${a.quantity}</td>
      <td>${priceDisplay}</td>
      <td>${totalValueDisplay}</td>
      <td class="${changeClass}">${changeDisplay}</td>
    `;
    tbody.appendChild(tr);
  });
}


  renderDashboard();
</script>

</body>
</html>
