<?php

/**
 * ��Ʒ�б�
 * @author��storm
 * Email��hi@yumufeng.com
 * Date: 2017/7/19 0019
 * Time: 9:41
 */
class Export_excel extends \Application\Component\Common\AdminPermissionValidateController
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('data/goods_data');
        $this->load->model('data/goods_sku_data');
    }


    //����
    public function daochu(){

        $ids = input('ids');
        $ids = trim($ids,',');

        //��ȡ��Ʒ����
        $spu_list = $this->goods_data->get_list_inids($ids);

        foreach($spu_list as $k=>$v){
            $spu_list[$k]['sku_list'] = $this->goods_sku_data->get_list_spuid($v['id']);
        }

        header("Content-type:application/vnd.ms-excel");   //������������Ϊexcel
        header("Content-Disposition:attachment;filename=excel����.xls");  //content-disposition����attachmentΪ�������أ�inlineʱ����Ƕ�������ʾ����Ȼ��jpg���ļ���Ч��excel�ļ�������Ƕ�������з����ĵ��˽⣻filename�����ļ���������չ��
        echo "SKU\t";
        echo "��Ʒ����\t";
        echo "SKU����\t";
        echo "������1\t";
        echo "����ֵ1\t";
        echo "������2\t";
        echo "����ֵ2\t";
        echo "SKU���Ա��\t";
        echo "��Ʒ����(g)\t";
        echo "�ɹ�����\t";
        echo "SKU���Ա���\t";
        echo "�ֿ�����1\t";
        echo "�������1\t";
        echo "��λ1\t";
        echo "�ֿ�����2\t";
        echo "�������2\t";
        echo "��λ2\t";
        echo "��Ʒ���(��*��*��)CM\t";
        echo "��Ʒ�ص�\t";
        echo "��ע\t";
        echo "��Ӧ������\t";
        echo "��С�ɹ���(MOQ)\t";
        echo "�ɹ�����\t";
        echo "����\t";
        echo "Ʒ��\t";
        echo "���Ա�ǩ\t";
        echo "�����������\t";
        echo "Ӣ���������\t";
        echo "���ı�����\t";
        echo "Ӣ�ı�����\t";
        echo "��װ��������\t";
        echo "��װ�ɱ�(CNY)\t";
        echo "��װ����(g)\t";
        echo "��װ�ߴ�(��*��*��)CM\t";
        echo "��Ʒ��ͼ\t";
        echo "ҵ�񿪷�Ա\t";
        echo "�ɹ�ѯ��Ա\t";
        echo "�ɹ�Ա\t";

        foreach($spu_list as $k=>$v){
            echo "\n";
            echo $v['code']."\t";//��Ʒ����
            echo iconv('utf-8', 'gbk', $v['name'])."\t";//��Ʒ����
            echo "\t";//����
            echo "\t";//������1
            echo "\t";//����ֵ1
            echo "\t";//������2
            echo "\t";//����ֵ2
            echo "\t";//���Ա��
            echo "\t";//��Ʒ����
            echo "\t";//�ɹ�����
            echo "\t";//SKU���Ա���
            echo "\t";//�ֿ�����1
            echo "\t";//�������1
            echo "\t";//��λ1
            echo "\t";//�ֿ�����2
            echo "\t";//�������2
            echo "\t";//��λ2
            echo "\t";//��Ʒ���(��*��*��)CM
            echo "\t";//��Ʒ�ص�
            echo iconv('utf-8', 'gbk', $v['remarks'])."\t";//��ע
            echo iconv('utf-8', 'gbk', $v['supplier_name'])."\t";//��Ӧ������
            echo "\t";//��С�ɹ���(MOQ)
            echo $v['source_address']."\t";//�ɹ�����
            echo "\t";//����
            echo "\t";//Ʒ��
            echo "\t";//���Ա�ǩ
            echo "\t";//�����������
            echo "\t";//Ӣ���������
            echo iconv('utf-8', 'gbk', $v['c_name'])."\t";//���ı�����
            echo $v['c_name_en']."\t";//Ӣ�ı�����
            echo "\t";//��װ��������
            echo "\t";//��װ�ɱ�(CNY)
            echo "\t";//��װ����(g)
            echo "\t";//��װ�ߴ�(��*��*��)CM
            echo "\t";//��Ʒ��ͼ
            echo "\t";//ҵ�񿪷�Ա
            echo "\t";//�ɹ�ѯ��Ա
            echo "\t";//�ɹ�Ա

            foreach($v['sku_list'] as $i=>$item){
                echo "\n";
                echo "\t";//��Ʒ����
                echo "\t";//��Ʒ����
                echo "\t";//����
                echo "���\t";//������1
                echo iconv('utf-8', 'gbk', $item['norms'])."\t";//����ֵ1
                echo "\t";//������2
                echo "\t";//����ֵ2
                echo $item['code']."\t";//���Ա��
                echo $item['weight']."\t";//��Ʒ����
                echo $item['price']."\t";//�ɹ�����
                echo iconv('utf-8', 'gbk', $item['alias'])."\t";//SKU���Ա���
                echo "\t";//�ֿ�����1
                echo "\t";//�������1
                echo "\t";//��λ1
                echo "\t";//�ֿ�����2
                echo "\t";//�������2
                echo "\t";//��λ2
                echo "\t";//��Ʒ���(��*��*��)CM
                echo "\t";//��Ʒ�ص�
                echo "\t";//��ע
                echo "\t";//��Ӧ������
                echo "\t";//��С�ɹ���(MOQ)
                echo "\t";//�ɹ�����
                echo "\t";//����
                echo "\t";//Ʒ��
                echo "\t";//���Ա�ǩ
                echo "\t";//�����������
                echo "\t";//Ӣ���������
                echo "\t";//���ı�����
                echo "\t";//Ӣ�ı�����
                echo "\t";//��װ��������
                echo "\t";//��װ�ɱ�(CNY)
                echo "\t";//��װ����(g)
                echo "\t";//��װ�ߴ�(��*��*��)CM
                echo "\t";//��Ʒ��ͼ
                echo "\t";//ҵ�񿪷�Ա
                echo "\t";//�ɹ�ѯ��Ա
                echo "\t";//�ɹ�Ա

            }
        }
    }

}