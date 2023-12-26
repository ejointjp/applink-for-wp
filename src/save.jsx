import { useBlockProps } from '@wordpress/block-editor';
import Applink from './components/Applink';

export default function save(props) {
  const blockProps = useBlockProps.save();

  return (
    <div {...blockProps}>
      <Applink app={props.attributes.app} />
    </div>
  );
}
