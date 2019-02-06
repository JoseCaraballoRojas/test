/**
 * @author Jose Caraballo
 * @description Container para la caja de alfajor
 */

/**
 * @description Libreria para peteciones asincronas
 */
import axios from "axios";

/**
 * @description Componentes de material-ui
 */
import Grid from "@material-ui/core/Grid";
import Card from "@material-ui/core/Card";
import Paper from "@material-ui/core/Paper";
import CardHeader from "@material-ui/core/CardHeader";
import CardContent from "@material-ui/core/CardContent";
import Table from "@material-ui/core/Table";
import TableHead from "@material-ui/core/TableHead";
import TableBody from "@material-ui/core/TableBody";
import TableRow from "@material-ui/core/TableRow";
import TableCell from "@material-ui/core/TableCell";
import Tooltip from "@material-ui/core/Tooltip";
import Fab from "@material-ui/core/Fab";
import AddIcon from "@material-ui/icons/Add";
import TextField from "@material-ui/core/TextField";
import Checkbox from "@material-ui/core/Checkbox";

import React, { Component } from "react";

class Box extends Component {
  state = {
    box: {
      price: 0,
      product: []
    },
    checked: false,
    rows: 3,
    columns: 3,
    top: [],
    products: []
  };

  componentDidMount() {
    this.getBox();
  }

  async getBox() {
    const { rows, columns } = this.state;
    const config = {
      headers: {
        "Content-Type": "application/json",
        Accept: "application/json"
      },
      mode: "no-cors"
    };

    const response = await axios
      .post(
        "http://127.0.0.1:8000/api/v1/boxes/",
        { price: 0, rows, columns },
        config
      )
      .catch(function(error) {
        if (!error.status) {
          console.log(error);
        }
      });
    console.log(response);
    
    const top = await axios
      .get("http://127.0.0.1:8000/api/v1/boxes/top", config)
      .catch(function(error) {
        if (!error.status) {
          console.log(error);
        }
      });

    const products = await axios
      .get("http://127.0.0.1:8000/api/v1/products", config)
      .catch(function(error) {
        if (!error.status) {
          console.log(error);
        }
      });

    this.setState({
      box: {
        price: response.data.data.price_box,
        product: response.data.data.product
      },
      top: top.data.data,
      products: products.data.data
    });
  }

  generateTableExample = (rows, columns) => {
    const general = [];
    for (let i = 0; i < rows; i++) {
      general.push([]);
      for (let j = 0; j < columns; j++) {
        general[i].push({ taste: "example", position: "0" });
      }
    }

    return general;
  };

  handleGetCajas = () => {
    this.getBox();
  };

  handleChecked = () => {
    const { checked } = this.state;

    this.setState({ checked: !checked });
  };

  handleInput = e => {
    e.preventDefault();
    const { name, value } = e.target;
    this.setState(
      {
        [name]: value
      },
      () => {
        const { rows, columns } = this.state;
        const product = this.generateTableExample(rows, columns);
        this.setState({ box: { product, price: 0 } });
      }
    );
  };

  render() {
    const { box, checked, rows, columns, top, products } = this.state;
    return (
      <div justify="center">
          <Card className="margin-top">
            <CardHeader
              title="Caja de Alfajor"
              action={
                <Tooltip title="Generar Caja" aria-label="Generar Caja">
                  <Fab color="primary" onClick={() => this.handleGetCajas()}>
                    <AddIcon />
                  </Fab>
                </Tooltip>
              }
            />
            <Checkbox checked={checked} onChange={() => this.handleChecked()} />
            <TextField
              name="rows"
              value={rows}
              disabled={!checked}
              onChange={this.handleInput}
              label="Hileras"
            />
            <TextField
              name="columns"
              value={columns}
              disabled={!checked}
              onChange={this.handleInput}
              label="Alfajores por hilera"
            />
            <CardContent>
              <Paper>
                <Table>
                  <TableBody>
                    {box.product.map((element, key) => {
                      return (
                        <TableRow key={key}>
                          {element.map((e, key) => (
                            <TableCell key={key}>
                              {e.taste}
                            </TableCell>
                          ))}
                        </TableRow>
                      );
                    })}
                    {box.product.length && (
                      <TableRow>
                        <TableCell colSpan={columns}>
                          <strong>Precio ($):</strong> {box.price.toFixed(2)}
                        </TableCell>
                      </TableRow>
                    )}
                  </TableBody>
                </Table>
              </Paper>
            </CardContent>
          </Card>
          <Card className="margin-top">
          <CardHeader title="TOP 3 Mejores Cajas" />
            <CardContent>
              <Paper>
                <Table>
                  <TableHead>
                    <TableRow>
                      <TableCell>ID</TableCell>
                      <TableCell>Precio ($)</TableCell>
                    </TableRow>
                  </TableHead>
                  <TableBody>
                    {Array.isArray(top) &&
                      top.map((element, key) => {
                        return (
                          <TableRow key={key}>
                            <TableCell>{element.id}</TableCell>
                            <TableCell>{element.price}</TableCell>
                          </TableRow>
                        );
                      })}
                  </TableBody>
                </Table>
              </Paper>
            </CardContent>
          </Card>
          <Card className="margin-top">
            <CardHeader title="Alfajores Dsiponibles" />
            <CardContent>
              <Paper>
                <Table>
                  <TableHead>
                    <TableRow>
                      <TableCell>ID</TableCell>
                      <TableCell>Gusto</TableCell>
                      <TableCell>Letra</TableCell>
                      <TableCell>Valor ($)</TableCell>
                    </TableRow>
                  </TableHead>
                  <TableBody>
                  {Array.isArray(products) &&
                      products.map((element, key) => {
                        return (
                          <TableRow key={key}>
                            <TableCell>{element.id}</TableCell>
                            <TableCell>{element.taste}</TableCell>
                            <TableCell>{element.letter}</TableCell>
                            <TableCell>{element.value}</TableCell>
                          </TableRow>
                        );
                      })}
                  </TableBody>
                </Table>
              </Paper>
            </CardContent>
          </Card>
      </div>
    );
  }
}

export default Box;
