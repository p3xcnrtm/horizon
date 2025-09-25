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

  
  <script>
const LCW_API_KEY = "4d740457-16d7-483f-8c63-0e3705c4bc7c"; // replace with your LiveCoinWatch API key

let assets = [
  { symbol: "BTC", name: "Bitcoin", type: "Crypto", quantity: <?php echo $_SESSION['btc']; ?>, price: 0, change: 0 },
  { symbol: "ETH", name: "Ethereum", type: "Crypto", quantity: <?php echo $_SESSION['eth']; ?>, price: 0, change: 0 },
  { symbol: "DOGE", name: "Dogecoin", type: "Crypto", quantity: <?php echo $_SESSION['dodge']; ?>, price: 0, change: 0 },
  { symbol: "USDT", name: "Tether", type: "Crypto", quantity: <?php echo $_SESSION['usdt']; ?>, price: 0, change: 0 },
  { symbol: "XLM", name: "Stellar", type: "Crypto", quantity: <?php echo $_SESSION['xlm']; ?>, price: 0, change: 0 }
];



async function fetchLivePrices() {
  try {
    const res = await fetch("https://api.livecoinwatch.com/coins/map", {
      method: "POST",
      headers: {
        "content-type": "application/json",
        "x-api-key": LCW_API_KEY
      },
      body: JSON.stringify({
        currency: "USD",
        codes: assets.map(a => a.symbol),
        sort: "rank",
        order: "ascending",
        offset: 0,
        limit: assets.length,
        meta: true
      })
    });

    const data = await res.json();

    data.forEach(coin => {
      const asset = assets.find(a => a.symbol === coin.code);
      if (asset) {
        asset.price = coin.rate || 0;                          // live price
        asset.change = (coin.delta && coin.delta.day)          // live change
          ? coin.delta.day * 100
          : 0;
      }
    });

    renderDashboard();
  } catch (e) {
    console.error("Failed to fetch prices", e);
  }
}

</script>
  <script>
function renderDashboard() {
  const tbody = document.getElementById("assets-table");
  tbody.innerHTML = ""; // clear old rows

  assets.forEach(asset => {
    const totalValue = (asset.quantity * asset.price).toFixed(2);
    const changeClass = asset.change >= 0 ? "positive" : "negative";

    const row = `
      <tr>
        <td>${asset.name} (${asset.symbol})</td>
        <td>${asset.type}</td>
        <td>${asset.quantity}</td>
        <td>$${asset.price.toFixed(4)}</td>
        <td>$${totalValue}</td>
        <td class="${changeClass}">${asset.change.toFixed(2)}%</td>
      </tr>
    `;
    tbody.insertAdjacentHTML("beforeend", row);
  });
}

// fetch immediately on page load
fetchLivePrices();
// refresh every 1 min
setInterval(fetchLivePrices, 60000);
</script>


</body>
</html>
