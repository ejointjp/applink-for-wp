import Applink from './components/Applink'

const { useBlockProps } = wp.blockEditor

export default function save (props) {
  const blockProps = useBlockProps.save()

  return (
    <div {...blockProps}>
      <Applink app={props.attributes.app} />
    </div>
  )
}
