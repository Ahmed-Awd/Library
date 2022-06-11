import axios from "axios";
import { ref } from "vue";

// algorithm
// inputs
//  order: exist object from localStorage
//  itemData: order related info from api (all option_templates, option_categories, ....)

export const selectedOrder = ref<Record<string, any>>({});
export const selectedItemData = ref<Record<string, any>>({});
export const primariesNum = ref<boolean>(false);
export const selectedRestaurantCurrency = ref("");
export const myOrders = ref<Record<string, any>[]>([]);

function fillSecondaryArr() {
  selectedItemData.value.secondaryArr = [];
  selectedItemData.value.secondaries.map((el) => {
    el.secondary_option_value.map((item) => {
      if (
        selectedItemData.value.selectedSecondary.includes(
          item.option_secondary_id
        )
      ) {
        if (
          selectedItemData.value.secondaryArr.findIndex(
            (x) => x.item_id == item.option_secondary_id
          ) === -1
        ) {
          selectedItemData.value.secondaryArr.push({
            sec_id: el.id,
            item_id: item.option_secondary_id,
            quantity:
              item.qnt <= item.max_count * selectedItemData.value.count
                ? item.qnt
                : 1,
            option_name: item.name,
            price: item.price,
          });
        } else {
          selectedItemData.value.secondaryArr[
            selectedItemData.value.secondaryArr.findIndex(
              (x) => x.item_id == item.option_secondary_id
            )
          ].quantity++;
        }
      }
    });
  });
}

export function updateSelectedItemInCart(): void {
  const myOrdersJson = localStorage.getItem("myOrder");
  if (myOrdersJson !== null) myOrders.value = JSON.parse(myOrdersJson);

  fillSecondaryArr();
  selectedOrder.value = {
    restaurant_id: selectedItemData.value.restaurant_id,
    item_id: selectedItemData.value.id,
    item_qty: selectedItemData.value.count,
    primary_value_option_id: selectedItemData.value.primaries.id,
    primary_option_value_quantity: 1,
    note: selectedItemData.value.notes,
    order_items: selectedItemData.value.secondaryArr,
    itemName: selectedItemData.value.name,
    restaurantName: selectedItemData.value.restaurant_name,
    itemImg: selectedItemData.value.image,
    item_price: selectedItemData.value.price,
    // condition if item has offer
    ...(selectedItemData.value.current_offer && {
      new_price: selectedItemData.value.current_offer.new_price,
    }),
    restaurantCurrency: selectedRestaurantCurrency.value,
    categories: selectedItemData.value.categories,
    catOptions: selectedItemData.value.catOptions,
  };
  if (Array.isArray(myOrders.value)) {
    myOrders.value = myOrders.value.map((order) => {
      if (order.itemName === selectedOrder.value.itemName) {
        order = selectedOrder.value;
      }
      return order;
    });
  }
  localStorage.setItem("myOrder", JSON.stringify(myOrders.value));
}

// processes:
//  inital merge:
//   - when click edit button
//   - set selected order to order related to button
//   - fetch itemData related to order
//   - set count, note, primries, secondaries,
//     and quantities of each sub option in selecteditemData

export function fetchItemData(): void {
  if (selectedOrder.value.itemName) {
    axios
      .get(`menu/item/${selectedOrder.value.item_id}`)
      .then(({ data }) => {
        const item = {
          ...data.item,
          // total count
          count: selectedOrder.value.item_qty,
          // option primary
          primaries: "",
          // secondaries depending on primary
          secondaries: [],
          notes: selectedOrder.value.note,
          selectedSecondary: [],
          secondaryArr: [],
          categories: selectedOrder.value.categories,
          catOptions: selectedOrder.value.catOptions,
          catOptionsArr: [],
        };
        if (item && item.option_template_id == null) {
          primariesNum.value = false;
        } else {
          primariesNum.value = true;
        }
        initComponentData(item);
        // @ts-ignore
        setTimeout(() => window.$("#editItemInCart").modal("show"), 100);
      })
      .catch((errors) => {
        console.log(errors);
      });
  }
}

function initComponentData(item) {
  if (selectedOrder.value.order_items) {
    selectedOrder.value.order_items.forEach((op) => {
      item.selectedSecondary.push(op.item_id);
    });
    selectedItemData.value = { ...item };
    checkPrimaryBySecondaryList();
    setQuantities();
  }
}

function setQuantities() {
  selectedItemData.value.secondaries.map((el) => {
    el.secondary_option_value.map((item) => {
      if (
        selectedItemData.value.selectedSecondary.includes(
          item.option_secondary_id
        )
      ) {
        item.qnt = selectedOrder.value.order_items.filter(
          (itm) => itm.item_id === item.option_secondary_id
        )[0]?.quantity;
      } else {
        item.qnt = 1;
      }
    });
  });
}

function checkPrimaryBySecondaryList() {
  if (selectedItemData.value.option_secondaries)
    selectedItemData.value.option_secondaries.forEach((prim) => {
      if (prim.id == selectedOrder.value.primary_value_option_id) {
        selectedItemData.value.primaries = prim;
        getOptions();
      }
    });
}
export function getOptions(): void {
  primariesNum.value = false;
  selectedItemData.value.secondaries =
    selectedItemData.value.primaries.secondary_option;
}

//  quantities
//   - use setup
//   - change as selectedItemData as needed from any where

//   - when selectedItemData change extract info as needed and commit it
//     to myorders localstorage
export function editSelectedOrderInfo(): void {
  const ordersJson = localStorage.getItem("myOrders");
  if (ordersJson !== null) {
    let myOrders = JSON.parse(ordersJson);
    myOrders = myOrders.map((order) => {
      if (order.itemName == selectedOrder.value.itemName) {
        if (order.order_items.length) {
          order.order_items = selectedOrder.value.order_items;
          order.primary_value_option_id =
            selectedOrder.value.primary_value_option_id;
        } else if (order.catOptions.length) {
          order.catOptions = selectedOrder.value.catOptions;
          order.categories = selectedOrder.value.categories;
        }
      }
      return order;
    });
    localStorage.setItem("myOrder", JSON.stringify(myOrders));
  }
}
