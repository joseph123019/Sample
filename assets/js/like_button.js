class Running_Order extends React.Component {
  constructor(props) {
    super(props);

    this.state = {
      show: false,
      orders: [],
      order_details: [],
      items: [],
      categories: [],
      tables: [],
      isLoading: false,
      table: '',
      now: new Date().toLocaleDateString(),
      count: []
    };
    this.addToOrder = this.addToOrder.bind(this);
    this.changeTable = this.changeTable.bind(this);
    this.submitNewCustomer = this.submitNewCustomer.bind(this);
    this.checkout = this.checkout.bind(this);
    this.addSeniorDiscount = this.addSeniorDiscount.bind(this);
  }

  componentDidMount() {
    fetch(baseURL+'Running_order_api/cons')
    .then(response => response.json())
    .then(data =>
      this.setState({
        orders: data.orders,
        order_details: data.order_details,
        items: data.items,
        categories: data.categories,
        tables: data.tables,
        isLoading: true,
        table: data.table ? data.table : ''
      })
    );
    //dialog_support.init("a.modal-dlg, button.modal-dlg");
  }

  Capitalize(str){
    return str.charAt(0).toUpperCase() + str.slice(1);
  }

  lowerCase(lwr){
    return lwr.replace(/\s+/g, '-').toLowerCase();
  }

  removeOneQty(){
    alert("Im an removeOneQty");
  }

  removeItem(){
    alert("Im an removeItem");
  }

  clearOrder(){
    alert("Im an clearOrder");
  }

  checkout(){
    let count = this.state.count
    count.push("new element");
    this.setState({ count: count})
  }

  addSeniorDiscount(){
    alert('test');
  }

  getOrderList()
  {
    //get all order if(length > 0) THEN retun no order on this table else() then get item ordered
  }
    //<table>
      //{this.createCategories()}
    //</table>

  addToOrder(item_id, order_id){
    let form = this;

    var params = {
      item_id: item_id,
      order_id: order_id
    };
    fetch(baseURL+'Running_order_api/order_item', {
      method: 'post',
      body: JSON.stringify(params),
      beforeSend: function() {
        alert('test');
      }
    }).then(function(response) {
      return response.json();
    }).then(function(data) {
      form.setState({
          order_details: data.order_details,
          isLoading: true,
        })
    });    
  }

  decreaseOrder(item_id, order_id){
    let form = this;

    var params = {
      item_id: item_id,
      order_id: order_id
    };
    fetch(baseURL+'Running_order_api/decrease_order_item', {
      method: 'post',
      body: JSON.stringify(params),
      beforeSend: function() {
        alert('test');
      }
    }).then(function(response) {
      return response.json();
    }).then(function(data) {
      form.setState({
          order_details: data.order_details,
          isLoading: true,
        })
    });    
  }

  deleteOrder(item_id, order_id){
    let form = this;

    var params = {
      item_id: item_id,
      order_id: order_id
    };
    fetch(baseURL+'Running_order_api/delete_order_item', {
      method: 'post',
      body: JSON.stringify(params)
    }).then(function(response) {
      return response.json();
    }).then(function(data) {
      form.setState({
          order_details: data.order_details,
          isLoading: true,
        })
    });    
  }

  createCategoryTabList()
  {
    let categoriesData = this.state.categories;
    let categoriesList = [];
    if(categoriesData)
    {
      Object.keys(categoriesData).forEach(function(key, index) {
        categoriesList.push(<li role="presentation" key={`categorylist${categoriesData[key].category_id}`.toString()}><a href={`#${categoriesData[key].name}`.replace(/\s+/g, '-').toLowerCase()} role="tab" data-toggle="tab">{categoriesData[key].name}</a></li>)
      });
    }
    return categoriesList
  }
  
  createOrderList()
  {
    let orderData = this.state.orders;
    let orderDetailData = this.state.order_details;
    let table = this.state.table;
    let orderDetailList = [];
    let orderList = [];
    let form = this;
    if(orderData.length > 0)
    {
      if(orderDetailData.length > 0)
      {
        Object.keys(orderDetailData).forEach(function(key, index) {
          //categoriesList.push(<li role="presentation" key={`${categoriesData[key].category_id}`.toString()}><a href={`#${categoriesData[key].name}`.replace(/\s+/g, '-').toLowerCase()} role="tab" data-toggle="tab">{categoriesData[key].name}</a></li>)
          orderList.push(<li key={`li${orderDetailData[key].name}`.toString()} className="list-group-item">
                          <div key={`divrow${orderDetailData[key].name}`.toString()} className="row">
                            <div key={`${orderDetailData[key].name}${orderDetailData[key].quantity_purchased}`.toString()} className="col-md-1"><span className="badge badge-left">{Math.round(`${orderDetailData[key].quantity_purchased}`.toString())}</span></div>
                            <div key={`${orderDetailData[key].name}`.toString()} className="col-md-4">{orderDetailData[key].name}</div>
                            <div key={`${orderDetailData[key].name}{orderDetailData[key].item_price}`.toString()} className="col-md-1"><div className="label label-success">&#8369; {parseFloat(orderDetailData[key].item_price).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')}</div></div>
                            <div key={`total${orderDetailData[key].item_price}`.toString()} className="col-md-1 col-md-push-1"><div className="label label-warning">&#8369; {(orderDetailData[key].quantity_purchased * orderDetailData[key].item_price).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')}</div></div>
                            <div key={`tab${orderDetailData[key].name}`.toString()} className="col-md-2 col-md-push-2">
                              <button key={`increase${orderDetailData[key].name}`.toString()} className="btn btn-success btn-xs" onClick={() => form.addToOrder(`${orderDetailData[key].item_id}`, `${orderData[0].order_id}`)}>
                                  <span className="glyphicon glyphicon-plus"></span>
                              </button>
                              <button key={`decrease${orderDetailData[key].name}`.toString()} className="btn btn-warning btn-xs" onClick={() => form.decreaseOrder(`${orderDetailData[key].item_id}`, `${orderData[0].order_id}`)}>
                                  <span className="glyphicon glyphicon-minus"></span>
                              </button>
                            </div>
                            <div key={`remove${orderDetailData[key].name}`.toString()} className="col-md-1 col-md-push-2" onClick={() => form.deleteOrder(`${orderDetailData[key].item_id}`, `${orderData[0].order_id}`)}>
                              <button className="btn btn-danger btn-xs">
                                  <span className="glyphicon glyphicon-trash"></span>
                              </button>
                            </div>
                          </div>
                        </li>)
        });
      }
      else
      {
        orderList.push(<div key="noy" className="text-warning">Nothing ordered yet!</div>);
      }  
    }
    else
    {
      orderList.push(<form onSubmit={form.submitNewCustomer} key="submitNewCustomer">
                      <label>
                        <input key="cname" type="text" name="cname" placeholder="Customer Name" ref="cname" />
                      </label>
                      <label>
                        <input key="pax" type="number" name="pax" placeholder="Pax" ref="pax" />
                      </label>
                      <input key="submitcustomer" type="submit" value="Confirm" />
                    </form>);
    }
    return orderList
  }

  getTotal()
  {
    let orderDetailData = this.state.order_details;
    if(Array.isArray(orderDetailData) && orderDetailData.length > 0)
    {
      let totalvalue = 0;
      
      orderDetailData.forEach(function(val){
        totalvalue += (val.item_price * val.quantity_purchased);
      });
      return totalvalue.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');
    }
    else
    {
      return 0;
    }
  }

  getTotalOrders()
  {
    let orderDetailData = this.state.order_details;
    let totalOrder = 0;
    if(Array.isArray(orderDetailData) && orderDetailData.length > 0)
    {
      totalOrder = orderDetailData.length;
      return totalOrder;
    }
    else
    {
      return 0;
    }
  }

  submitNewCustomer(e){
    var form = this;
    let table = form.state.table;

    e.preventDefault();

    let cname = form.refs.cname.value;
    let pax = form.refs.pax.value;
    
    if(cname == '' || pax == '')
    {
      if(pax == '')
      {
        alert('Pax Cannot be Blank')
      }
      if(cname == '')
      {
        alert('Customer Name Cannot be Blank')
      }
    }
    else
    {
      var params = {
        pax: pax,
        cname: cname,
        table: table
      };

      fetch(baseURL+'Running_order_api/add_new_customer', {
        method: 'post',
        body: JSON.stringify(params)
      }).then(function(response) {
        return response.json();
      }).then(function(data) {
        form.setState({
          orders: data.orders,
          isLoading: true,
        })
      });
    }
  }

  createTablContent()
  {
    let categoriesTabData = this.state.categories;
    let itemButtonData = this.state.items;
    let categoriesTabList = [];
    let form = this;
    let orderData = this.state.orders;

    if(categoriesTabData)
    {
      Object.keys(categoriesTabData).forEach(function(key) {
        let children = []
        //Inner loop to create children
        Object.keys(itemButtonData).forEach(function(item_key) {
          if(itemButtonData[item_key].category_id == categoriesTabData[key].category_id){
            children.push(<button className="btn btn-primary btn-marginTop" key={`buttonlist${itemButtonData[item_key].item_id}`.toString()} value={`${itemButtonData[item_key].item_id}`} onClick={() => form.addToOrder(`${itemButtonData[item_key].item_id}`, `${orderData[0].order_id}`)}>{itemButtonData[item_key].name}</button>)
          }
        });
        categoriesTabList.push( <div role="tabpanel" className="tab-pane" key={`buttontab${categoriesTabData[key].category_id}`.toString()} id={`${categoriesTabData[key].name}`.replace(/\s+/g, '-').toLowerCase()}>
          {children}
        </div>)
      });
    }

    return categoriesTabList
  }
    //<button className="btn btn-primary btn-pos btn-marginTop" data-ng-repeat="item in foods" value={`${categoriesData[key].name}`} data-ng-click="addToOrder(item,1)">{categoriesTabData[key].name}</button>
  createCategories(){
    let categoriesList = []

    for (let i = 0; i < this.state.categories.length; i++) {
      let children = []
      //Inner loop to create children
      for (let j = 0; j < 5; j++) {
        children.push(<td>{`Button ${j + 1}`}</td>)
      }
      //Create the parent and add the children
      categoriesList.push(<tr>{children}</tr>)
    }
    return categoriesList
  }

  createCategoryOption(){
    let categoryOptionsData = this.state.categories;
    let categoryOptions = [];

    if(categoryOptionsData)
    {
      Object.keys(categoryOptionsData).forEach(function(key, index) {
        categoryOptions.push(<option key={`categoryoption${categoryOptionsData[key].category_id}`.toString()}>{`${categoryOptionsData[key].name}`}</option>)
      });
    }
    return categoryOptions
  }

  createTables(){
    let tableOptionsData = this.state.tables;
    let tableOptions = [];
    if(tableOptionsData)
    {
      Object.keys(tableOptionsData).forEach(function(key, index) {
        tableOptions.push(<option key={`tableoption${tableOptionsData[key].order_table_id}`.toString()}>{`${tableOptionsData[key].name}`}</option>)
      });
    }
    return tableOptions
  }

  changeTable(){
    var form = this;
    var tableParams = {
      table: event.target.value
    };
    var table = event.target.value;

    fetch(baseURL+'Running_order_api/change_table', {
      method: 'post',
      body: JSON.stringify(tableParams)
    }).then(function(response) {
      return response.json();
    }).then(function(data) {
      form.setState({
        order_details: data.order_details,
        orders: data.orders,
        isLoading: true,
        table: table
      })
    });
  }

  render() {
    const { isLoading, categories } = this.state;
    return <React.Fragment>
      <div className="col-md-12">
      
        <div className="row">
          <div className="col-md-6">
              <div className="panel panel-primary">
                <div className="panel-body">
                  <ul id="myTab" className="nav nav-tabs" role="tablist">
                    {this.createCategoryTabList()}
                  </ul>
                  <div className="tab-content">
                    {this.createTablContent()}
                    <div role="tabpanel" className="tab-pane active" id="drink">
                      
                    </div>
                    <div role="tabpanel" className="tab-pane" id="dessert">
                      <button className="btn btn-primary btn-pos btn-marginTop" data-ng-repeat="item in desserts" data-ng-bind="item.name" data-ng-click="addToOrder(item,1)"></button>
                    </div>
                    <div role="tabpanel" className="tab-pane" id="new">
                      <br />
                      <form className="form" name="formCreate" noValidate>
                        <div className="col-md-3">
                            <label>Category</label>
                            <select className="form-control" onChange={this.changeTable} value={this.state.category} required >
                                {this.createCategoryOption()}
                            </select>
                        </div>
                        <div className="col-md-1">
                            <br />
                            <button className="btn btn-default btn-marginTop" data-ng-click="addNewItem(new)" data-ng-disabled="formCreate.$invalid">Add</button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
                <div className="panel-footer"></div>
              </div>
          </div>
          <div className="col-md-6">
            <div className="panel panel-primary">
              <div className="panel-heading">
                <div className="row">
                    <div className="col-md-4"><span className="panel-title">Table</span>
                      <span><select className="form-control" onChange={this.changeTable} value={this.state.table} required>
                          {this.createTables()}
                      </select></span>
                    </div>
                      
                    <div className="col-md-4"><span>Today is: {this.state.now}</span></div>
                    <div className="col-md-3 col-md-push-1"><span>Total Orders - </span><span className="badge">{this.getTotalOrders()}</span></div>
                </div>
              </div>
              <div className="panel-body" style={{maxHeight:'320px', overflow:'auto'}}>
                <ul className="list-group">
                  {this.createOrderList()}
                </ul>
              </div>
              <div className="panel-footer" ng-show="order.length">
                  <h3><span className="label label-primary">Total: &#8369;{this.getTotal()}</span></h3>
              </div>
              <div className="panel-footer" ng-show="order.length">
                  <div>
                      <span className="btn btn-default btn-marginTop" onClick={this.clearOrder}>Clear</span>
                      <span className="btn btn-danger btn-marginTop pull-right" onClick={this.checkout}>Checkout</span>
                  </div>
              </div>
            </div>
          </div>
          </div>
      </div>
    </React.Fragment>
  }
}

class LikeButton extends React.Component {
  constructor(props) {
    super(props);
    this.state = { liked: false,
      now: new Date() };
  }

  componentDidMount() {
    console.log('test');
  }

  render() {
    return React.createElement(
      'div',
      null,
      'Seconds: ',
      this.state.now.toLocaleDateString()
    );
  }
}

ReactDOM.render(
    <Running_Order />, 
    document.getElementById("root")
);