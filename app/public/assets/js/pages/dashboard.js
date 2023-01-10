import dashboard from "../components/dashboard.js";
import cardDeck from "../components/card-deck.js";
import teamsDropdown from "../components/teams-dropdown.js";

const handleResize = () => {
  if ($(window).width() >= 768) {
    // Bootstrap's "md" breakpoint
    $("#navbarNav").addClass("show");
  } else {
    $("#navbarNav").removeClass("show");
  }
};

const loadData = async () => {
  try {
    const response = await axios.get("/partner/api/shops");
    const shops = response.data;
    cardDeck.data.shops = shops;
    teamsDropdown.data.teams = shops;
  } catch (error) {
    console.error(error);
  }
};

const addItem = (categories, hazards, ingredients) => {
  new swal({
    title: "Add Item",
    html: /*html*/ `
        <input id="itemName" type="text" class="swal2-input" placeholder="Item Name">
        <input id="itemPrice" type="number" class="swal2-input" placeholder="Item Price">
        <select id="category" type="number" class="swal2-input swal2-select">
            <option>Category</option>
        </select>
        <select id="hazards" class="swal2-input swal2-select" multiple="multiple">
            <option>Hazard</option>
            <option>Hazard</option>
            <option>Hazard</option>
        </select>
        <select id="ingredient" class="swal2-input swal2-select" multiple="multiple">
            <option value="ing1">Ingredient</option>
            <option value="ing2">Ingredient</option>
            <option>Ingredient</option>
        </select>
    `,
    preConfirm: function () {
      return new Promise(function (resolve) {
        resolve([$("#swal-input1").val(), $("#swal-input2").val()]);
      });
    },
    onOpen: function () {
      $("#swal-input1").focus();
    },
  })
    .then(function (result) {
      swal(JSON.stringify(result));
    })
    .catch(swal.noop);
};

const main = () => {
  // component("#spa-container", dashboard.template);
  // component("#spa-container", cardDeck.template);
  component("#teams-dropdown", teamsDropdown.template);

  document
    .querySelector("#teams-dropdown")
    .addEventListener("reef:render", teamsDropdown.addEvents);
  document
    .querySelector("#spa-container")
    .addEventListener("reef:render", dashboard.addEvents);
  document.querySelector("#addItem").addEventListener("click", addItem);
};

$(window).on("load", main);
$(window).on("load", handleResize);
$(window).on("load", loadData);
$(window).on("resize", handleResize);
