/**
 * External dependencies
 */
import classnames from 'classnames';

function PanelColumns({className, children}) {
	const classes = classnames('components-panel__columns', className);

	return <div className={classes}>{children}</div>
}

export default PanelColumns;
