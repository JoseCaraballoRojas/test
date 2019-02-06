import React, { Component } from 'react';
import logo from './caja-para-regalo.svg';
import './App.css';
import Box from './containers/box/box';

class App extends Component {
  render() {
    return (
      <div className='App'>
        <header className='App-header'>
          <img src={logo} className='App-logo' alt='logo' />
          <Box />
        </header>
      </div>
    );
  }
}

export default App;
