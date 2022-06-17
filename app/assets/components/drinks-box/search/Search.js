import React from 'react';
import SearchBox from './SearchBox';
import Product from './Product';

const Search = () => {
  return (
    <div className="search">
      <div className="search__header">Wybierz produkty</div>
      <SearchBox />
      <div className="search__products-box">
        <Product name='whiskey' />
        <Product name='sok z cytryny' />
        <Product name='sok z cytryny' />
        <Product name='sok z cytryny' />
        <Product name='sok z cytryny' />
        <Product name='sok z cytryny' />
        <Product name='sok z cytryny' />
      </div>
    </div>
  );
};

export default Search;
