import cardDeck from "../components/card-deck.js";
import dashboard from "../components/dashboard.js";
import shopCart from "../components/shop-cart.js";
import sidebar from "../components/shops-sidebar.js";
import wishlist from "../components/wishlist.js";
import { emitTeamIdUpdated } from "../mixins/events.js";

const loadData = async () => {
  try {
    const response = await axios.get("/api/shops");
    const shops = response.data;
    cardDeck.data.shops = shops;
  } catch (error) {
    console.error(error);
  }
};

const config = () => {
  dashboard.data.isEditor = false;
}

const main = () => {
  config();
  component("#spa-container", dashboard.template);
  component("#sidebar-container", sidebar.template);
  component("#cart-dropdown", shopCart.template);
  component("#wishlist-dropdown", wishlist.template);

  document
    .querySelector("#spa-container")
    .addEventListener("reef:render", dashboard.addEvents);
  document
    .querySelector("#sidebar-container")
    .addEventListener("reef:render", sidebar.addEvents);
  document
    .querySelector("#cart-dropdown")
    .addEventListener("reef:render", shopCart.addEvents);
  document
    .querySelector("#wishlist-dropdown")
    .addEventListener("reef:render", wishlist.addEvents);

  $("#branding").on("click", function(e) {
    emitTeamIdUpdated(null);
    e.preventDefault();
  });
};

$(window).on("load", main);
$(window).on("load", loadData);
