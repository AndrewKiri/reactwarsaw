import * as React from 'react'

import styles from './style.scss'

const Layout: React.SFC = ({ children }) => (
  <div className={styles.example_green}>{children}</div>
)

export default Layout
